@extends('layouts.app')

@section('content')
<div class="card shadow-sm mx-auto" style="max-width: 600px;">
    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-dark">{{ isset($user) ? '✏️ Editar Usuario' : '👤 Nuevo Usuario' }}</h5>
        <a href="{{ route('users.index') }}" class="btn btn-sm btn-light">Volver al listado</a>
    </div>
    <div class="card-body">
        <form action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" method="POST">
            @csrf
            @if(isset($user)) 
                @method('PUT') 
            @endif

            <div class="mb-3">
                <label class="form-label">Nombre Completo</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
                <small class="text-muted">Recuerda: No se permiten números en el nombre de usuario.</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Correo Electrónico</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
            </div>

            @if(!isset($user))
                <div class="mb-3">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" placeholder="Mínimo 6 caracteres" required>
                </div>
            @endif
            
            <div class="d-flex justify-content-between align-items-center mt-4">
                <button type="submit" class="btn btn-info text-white">
                    {{ isset($user) ? 'Actualizar Usuario' : 'Registrar Usuario' }}
                </button>
        </form>

        @if(isset($user))
            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar a este usuario? Se desvinculará de sus préstamos activos.')">
                @csrf 
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">Eliminar</button>
            </form>
        @endif
            </div>
    </div>
</div>
@endsection