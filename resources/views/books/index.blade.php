@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Catálogo de Libros</h2>
    <a href="{{ route('books.create') }}" class="btn btn-primary">➕ Agregar Libro</a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Categorías</th>
                    <th>Estatus</th>
                    <th>Préstamo / Fila</th>
                    <th class="text-center">Acciones CRUD</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $book)
                    <tr>
                        <td><strong>{{ $book->name }}</strong></td>
                        <td>{{ $book->author }}</td>
                        <td>
                            @foreach($book->categories as $cat)
                                <span class="badge bg-secondary">{{ $cat->name }}</span>
                            @endforeach
                        </td>
                        <td>
                            @if($book->isAvailable())
                                <span class="badge bg-success">Disponible</span>
                            @else
                                <span class="badge bg-warning text-dark">Prestado a: {{ $book->user->name ?? 'Usuario' }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1" style="max-width: 200px;">
                                <form action="{{ route('books.toggle', $book->id) }}" method="POST" class="d-flex gap-1">
                                    @csrf
                                    @if($book->isAvailable())
                                        <select name="user_id" class="form-select form-select-sm" required>
                                            <option value="">¿A quién?</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-warning text-dark">Prestar</button>
                                    @else
                                        <button type="submit" class="btn btn-sm btn-success w-100">Devolver Libro</button>
                                    @endif
                                </form>

                               @if(!$book->isAvailable())
                                    <form action="{{ route('books.waitingList', $book->id) }}" method="POST" class="d-flex gap-1 mt-1">
                                        @csrf
                                        <select name="user_id" class="form-select form-select-sm" required>
                                            <option value="">Hacer fila...</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-secondary" title="Anotar en lista de espera">⏳ Fila</button>
                                    </form>
                                    
                                    @if($book->waitingList()->count() > 0)
                                        <small class="text-muted">👥 En fila de espera: <span class="badge bg-dark">{{ $book->waitingList()->count() }}</span></small>
                                    @endif
                                @endif

                            </div>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('books.edit', $book->id) }}" class="btn btn-sm btn-outline-primary">✏️ Editar</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center p-4 text-muted">No hay libros registrados en el sistema.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer bg-white pt-3 px-4">
        {{ $books->links() }}
    </div>
    </div>
@endsection