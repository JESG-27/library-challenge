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

    public function create()
    {
        $categories = Category::all();
        return view('books.create_edit', compact('categories'));
    }

    public function edit(Book $book)
    {
        $categories = Category::all();
        return view('books.create_edit', compact('book', 'categories'));
    }

    public function update(StoreBookRequest $request, Book $book)
    {
        $validated = $request->validated();

        $book->update([
            'name' => $validated['name'],
            'author' => $validated['author'],
            'publication_date' => $validated['publication_date'],
        ]);

        $book->categories()->sync($validated['categories']);

        return redirect()->route('books.index')->with('success', 'Libro actualizado con éxito.');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Libro eliminado con éxito.');
    }

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

        return redirect()->route('books.index')->with('success', 'Libro creado con éxito.');
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
            $nextUserInLine = $book->waitingList()->first();

            $book->update([
                'is_available' => true,
                'user_id' => null,
            ]);

            if ($nextUserInLine)
            {
                $message = "¡Buenas noticias, {$nextUserInLine->name}! El libro '{$book->name}' que estabas esperando ya se encuentra disponible para renta.";
                $this->messageSender->send($nextUserInLine->email, $message);
                $book->waitingList()->detach($nextUserInLine->id);    
            }
        }

        return redirect()->back()->with('success', 'Estatus del libro actualizado con éxito.');
    }

    public function joinWaitingList(Request $request, Book $book)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        if ($book->waitingList()->where('user_id', $request->user_id)->exists()){
            return redirect()->back()->withErrors(['waiting_list' => 'Este usuario ya está en la lista de espera para este libro.']);
        }

        if ($book->user_id == $request->user_id) {
            return redirect()->back()->withError(['waiting_list' => 'El usuario actual con el préstamo no puede anotarse en la lista de espera.']);
        }

        $book->waitingList()->attach($request->user_id);

        return redirect()->back()->with('success', 'Usuario añadido a la lista de espera con éxito.');
    }
}
