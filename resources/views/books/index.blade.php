@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">Nuevo Libro</div>
            <div class="card-body">
                <form action="{{ route('books.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Título del Libro</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Autor</label>
                        <input type="text" name="author" class="form-control" value="{{ old('author') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fecha de Publicación</label>
                        <input type="date" name="publication_date" class="form-control" value="{{ old('publication_date') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Categorías (Mínimo 1)</label>
                        <select name="categories[]" class="form-select" multiple size="4" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Mantén presionado Ctrl (o Cmd) para seleccionar varias.</small>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Guardar Libro</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">Catálogo de Libros</div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Título</th>
                            <th>Autor</th>
                            <th>Categorías</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
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
                                        <span class="badge bg-warning text-dark">Prestado a: {{ $book->user->name }}</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('books.toggle', $book->id) }}" method="POST" class="d-flex align-items-center mb-2">
                                        @csrf
                                        @if($book->isAvailable())
                                            <select name="user_id" class="form-select form-select-sm me-2" style="max-width: 130px;" required>
                                                <option value="">¿A quién?</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-outline-warning">Prestar</button>
                                        @else
                                            <button type="submit" class="btn btn-sm btn-outline-success">Devolver</button>
                                        @endif
                                    </form>

                                    @if(!$book->isAvailable())
                                        <form action="{{ route('books.waitingList', $book->id) }}" method="POST" class="d-flex align-items-center">
                                            @csrf
                                            <select name="user_id" class="form-select form-select-sm me-2" style="max-width: 130px;" required>
                                                <option value="">Esperar...</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-secondary" title="Anotar en lista de espera">⏳ Fila</button>
                                        </form>
                                        
                                        @if($book->waitingList()->count() > 0)
                                            <small class="text-muted d-block mt-1">👥 En fila: <span class="badge bg-secondary">{{ $book->waitingList()->count() }}</span></small>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center p-4 text-muted">No hay libros registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer bg-white pt-3">
                {{ $books->links() }}
            </div>
        </div>
    </div>
</div>
@endsection