<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class BookWaitingListTest extends TestCase
{
    use RefreshDatabase;

    /** test */
    public function un_usuario_puede_entrar_a_la_lista_de_espera_si_el_libro_esta_prestado()
    {
        $lectorA = User::factory()->create();
        $lectorB = User::factory()->create();
        $libro = Book::factory()->create(['is_available' => true]);

        $this->post(route('books.toggle', $libro->id), ['user_id' => $lectorA->id]);

        $response = $this->post(route('books.waitingList', $libro->id), ['user_id' => $lectorB->id]);

        $response->assertRedirect();
        $this->assertEquals(1, $libro->waitingList()->count());
    }

    /** @test */
    public function al_devolver_un_libro_se_notifica_al_primer_usuario_en_la_fila_fifo()
    {
        $lectorA = User::factory()->create();
        $lectorB = User::factory()->create(['name' => 'Juan Perez', 'email' => 'juan@ejemplo.com']);
        $lectorC = User::factory()->create();
        $libro = Book::factory()->create(['is_available' => false, 'user_id' => $lectorA->id]);

        $libro->waitingList()->attach($lectorB->id);
        $libro->waitingList()->attach($lectorC->id);

        Log::shouldReceive('info')->atLeast()->once();

        $response = $this->post(route('books.toggle', $libro->id));

        $libro = $libro->fresh();
        
        $this->assertTrue((bool)$libro->is_available, 'El libro debería volver a estar disponible');
        $this->assertNull($libro->user_id, 'El libro ya no debería tener un usuario asignado');

        $this->assertEquals(1, $libro->waitingList()->count(), 'Debería quedar solo 1 persona en la fila');
        $this->assertDatabaseMissing('book_user_waiting_list', ['user_id' => $lectorB->id]);
        $this->assertDatabaseHas('book_user_waiting_list', ['user_id' => $lectorC->id]);
    }
}
