@extends('layouts.app')

@section('content')
<div class="card shadow-sm mx-auto" style="max-width: 600px;">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ isset($category) ? '✏️ Editar Categoría' : '✨ Nueva Categoría' }}</h5>
        <a href="{{ route('categories.index') }}" class="btn btn-sm btn-light">Volver al listado</a>
    </div>
    <div class="card-body">
        <form action="{{ isset($category) ? route('categories.update', $category->id) : route('categories.store') }}" method="POST">
            @csrf
            @if(isset($category)) 
                @method('PUT') 
            @endif

            <div class="mb-3">
                <label class="form-label">Nombre de la Categoría</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $category->name ?? '') }}" required>
                <small class="text-muted">Recuerda: No se permiten números en el nombre.</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea name="description" class="form-control" rows="4" required>{{ old('description', $category->description ?? '') }}</textarea>
            </div>
            
            <div class="d-flex justify-content-between align-items-center mt-4">
                <button type="submit" class="btn btn-success">
                    {{ isset($category) ? 'Actualizar Categoría' : 'Guardar Categoría' }}
                </button>
        </form>

        {{-- Botón de Eliminar (Solo visible al editar) --}}
        @if(isset($category))
            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta categoría? Esto podría afectar a los libros asociados.')">
                @csrf 
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">Eliminar</button>
            </form>
        @endif
            </div>
    </div>
</div>
@endsection