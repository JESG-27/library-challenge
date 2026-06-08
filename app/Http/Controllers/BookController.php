<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use App\Services\MessageSender;
use Illuminate\Http\Request;

class BookController extends Controller
{
    protected MessageSender $messageSender;
    public function __construct(MessageSender $messageSender) 
    {
        $this->messageSender = $messageSender;
    }

    public function index()
    {
        $books = Book::with(['categories', 'user'])->paginate(5);
        $users = User::all();
        $categories = Category::all();

        return view('books.index', compact('books', 'users', 'categories'));
    }

    // Create
    public function store(StoreBookRequest $request)
    {
        $validated = $request->validated();

        $book = Book::create([
            'name' => $validated['name'],
            'author' => $validated['author'],
            'publication_date' => $validated['publication_date'],
            'is_available' => true,
        ]);

        $book->categories()->attach($validated['categories']);

        return redirect()->back()->with('success', 'Libro creado con éxito.');
    }

    public function toggleStatus(Request $request, Book $book)
    {
        $oldAvailableStatus = $book->is_available;

        if ($oldAvailableStatus)
        {
            $request->validate(['user_id' => 'required|exists:users,id']);

            $book->update([
                'is_available'=>false,
                'user_id'=>$request->user_id
            ]);
        }
        else
        {
            $book->update([
                'is_available' => true,
                'user_id' => null,
            ]);

            // Obtener un usuario en lista de espera  para enviar el mensaje
            $message = "Hola Nombre, el libro '{$book->name}' que tenías prestado ya está registrado como disponible nuevamente.";
            $this->messageSender->send('Numero', $message);
        }

        return redirect()->back()->with('success', 'Estatus del libro actualizado con éxito.');
    }
}
