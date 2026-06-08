@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>🗂️ Catálogo de Categorías</h2>
    <a href="{{ route('categories.create') }}" class="btn btn-success">➕ Nueva Categoría</a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th style="width: 30%;">Nombre</th>
                    <th style="width: 55%;">Descripción</th>
                    <th style="width: 15%;" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td><strong>{{ $category->name }}</strong></td>
                        <td>{{ \Illuminate\Support\Str::limit($category->description, 100) }}</td>
                        <td class="text-center">
                            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-outline-primary">✏️ Editar</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center p-4 text-muted">No hay categorías registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="card-footer bg-white pt-3 px-4">
        {{ $categories->links() }}
    </div>
</div>
@endsection