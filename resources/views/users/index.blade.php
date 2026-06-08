@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>👥 Usuarios de la Biblioteca</h2>
    <a href="{{ route('users.create') }}" class="btn btn-info text-white">➕ Registrar Usuario</a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th style="width: 45%;">Nombre Completo</th>
                    <th style="width: 40%;">Correo Electrónico</th>
                    <th style="width: 15%;" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td><span class="text-muted">{{ $user->email }}</span></td>
                        <td class="text-center">
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-primary">✏️ Editar</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center p-4 text-muted">No hay usuarios registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="card-footer bg-white pt-3 px-4">
        {{ $users->links() }}
    </div>
</div>
@endsection