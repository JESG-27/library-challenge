@extends('layouts.app')

@section('content')
<div class="card shadow-sm max-w-md mx-auto" style="max-width: 600px;">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <span>{{ isset($book) ? 'Editar Libro' : 'Nuevo Libro' }}</span>
        <a href="{{ route('books.index') }}" class="btn btn-sm btn-light">Volver</a>
    </div>
    <div class="card-body">
        <form action="{{ isset($book) ? route('books.update', $book->id) : route('books.store') }}" method="POST">
            @csrf
            @if(isset($book)) @method('PUT') @endif

            <div class="mb-3">
                <label class="form-label">Título del Libro</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $book->name ?? '') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Autor</label>
                <input type="text" name="author" class="form-control" value="{{ old('author', $book->author ?? '') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Fecha de Publicación</label>
                <input type="date" name="publication_date" class="form-control" 
                    value="{{ old('publication_date', isset($book) ? (\Illuminate\Support\Carbon::parse($book->publication_date)->format('Y-m-d')) : '') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Categorías (Mínimo 1)</label>
                <select name="categories[]" class="form-select" multiple size="4" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" 
                            {{ isset($book) && $book->categories->contains($category->id) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="d-flex justify-content-between mt-4">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>

        @if(isset($book))
            <form action="{{ route('books.destroy', $book->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este libro?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger">Eliminar Libro</button>
            </form>
        @endif
            </div>
    </div>
</div>
@endsection