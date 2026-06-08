<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function se_puede_crear_una_categoria_con_datos_validos()
    {
        $response = $this->post(route('categories.store'), [
            'name' => 'Novela Historica',
            'description' => 'Libros basados en hechos reales con elementos de ficcion.'
        ]);

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Novela Historica']);
    }

    /** @test */
    public function no_se_puede_crear_una_categoria_si_el_nombre_contiene_numeros()
    {
        $response = $this->post(route('categories.store'), [
            'name' => 'Ciencia Ficcion 123',
            'description' => 'Descripcion valida.'
        ]);

        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseCount('categories', 0);
    }

    /** @test */
    public function se_puede_actualizar_una_categoria_correctamente()
    {
        $categoria = Category::create([
            'name' => 'Drama',
            'description' => 'Descripcion antigua'
        ]);

        $response = $this->put(route('categories.update', $categoria->id), [
            'name' => 'Drama Absoluto',
            'description' => 'Nueva descripcion corregida'
        ]);

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseHas('categories', [
            'id' => $categoria->id,
            'name' => 'Drama Absoluto'
        ]);
    }

    /** @test */
    public function se_puede_eliminar_una_categoria()
    {
        $categoria = Category::create([
            'name' => 'Poesia',
            'description' => 'Libros de poemas.'
        ]);

        $response = $this->delete(route('categories.destroy', $categoria->id));

        $response->assertRedirect(route('categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $categoria->id]);
    }
}
