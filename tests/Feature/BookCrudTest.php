<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookCrudTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function se_puede_registrar_un_libro_con_sus_categorias()
    {
        $this->withoutExceptionHandling();
        $categoria1 = Category::create(['name' => 'Terror', 'description' => 'Susto']);
        $categoria2 = Category::create(['name' => 'Suspenso', 'description' => 'Intriga']);

        $response = $this->post(route('books.store'), [
            'name' => 'El Resplandor',
            'author' => 'Stephen King',
            'publication_date' => '1977-01-28',
            'categories' => [$categoria1->id, $categoria2->id]
        ]);

        $response->assertRedirect(route('books.index'));
        
        $this->assertDatabaseHas('books', ['name' => 'El Resplandor']);

        $libro = Book::where('name', 'El Resplandor')->first();
        $this->assertCount(2, $libro->categories);
    }

    /** @test */
    public function se_puede_actualizar_la_informacion_de_un_libro_y_sus_categorias()
    {
        $categoriaAntigua = Category::create(['name' => 'Antiguo', 'description' => 'Old']);
        $categoriaNueva = Category::create(['name' => 'Nuevo', 'description' => 'New']);
        
        $libro = Book::factory()->create([
            'name' => 'Titulo Original',
            'author' => 'Autor Original',
            'publication_date' => '2020-05-05'
        ]);
        $libro->categories()->attach($categoriaAntigua->id);

        $response = $this->put(route('books.update', $libro->id), [
            'name' => 'Titulo Editado',
            'author' => 'Autor Editado',
            'publication_date' => '2021-06-06',
            'categories' => [$categoriaNueva->id]
        ]);

        $response->assertRedirect(route('books.index'));
        
        $libro = $libro->fresh();
        $this->assertEquals('Titulo Editado', $libro->name);
        $this->assertTrue($libro->categories->contains($categoriaNueva->id));
        $this->assertFalse($libro->categories->contains($categoriaAntigua->id));
    }

    /** @test */
    public function se_puede_eliminar_un_libro_del_sistema()
    {
        $libro = Book::factory()->create();

        $response = $this->delete(route('books.destroy', $libro->id));

        $response->assertRedirect(route('books.index'));
        $this->assertDatabaseMissing('books', ['id' => $libro->id]);
    }
}