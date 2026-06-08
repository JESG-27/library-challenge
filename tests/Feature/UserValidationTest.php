<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class UserValidationTest extends TestCase
{
    use RefreshDatabase;

    /** test */
    public function un_usuario_puede_actualizar_su_perfil_manteniendo_su_propio_email()
    {
        $usuario = User::factory()->create([
            'name' => 'Carlos Gomez',
            'email' => 'carlos@ejemplo.com'
        ]);

        $response = $this->put(route('users.update', $usuario->id), [
            'name' => 'Carlos Gomez Editado',
            'email' => 'carlos@ejemplo.com'
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $usuario->id,
            'name' => 'Carlos Gomez Editado'
        ]);
    }

    /** @test */
    public function un_usuario_no_puede_cambiar_su_email_por_uno_que_ya_esta_ocupado()
    {
        $usuarioExistente = User::factory()->create(['email' => 'ocupado@ejemplo.com']);
        $miUsuario = User::factory()->create(['email' => 'mi_correo@ejemplo.com']);

        $response = $this->put(route('users.update', $miUsuario->id), [
            'name' => 'Intento de Fraude',
            'email' => 'ocupado@ejemplo.com'
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function se_puede_registrar_un_nuevo_usuario_y_su_contraseña_se_encripta()
    {
        $response = $this->post(route('users.store'), [
            'name' => 'Alejandro Perez',
            'email' => 'alex@ejemplo.com',
            'password' => 'secreto123'
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', ['email' => 'alex@ejemplo.com']);

        // Comprobamos que la contraseña NO se guardó en texto plano
        $usuario = User::where('email', 'alex@ejemplo.com')->first();
        $this->assertNotEquals('secreto123', $usuario->password);
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('secreto123', $usuario->password));
    }

    /** @test */
    public function se_puede_dar_de_baja_un_usuario()
    {
        $usuario = User::factory()->create();

        $response = $this->delete(route('users.destroy', $usuario->id));

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseMissing('users', ['id' => $usuario->id]);
    }
}
