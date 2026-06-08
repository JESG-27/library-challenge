<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(5);
        return view('users.index', compact('users'));
    }

public function store(StoreUserRequest $request)
{
    $validated = $request->validated();

    User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => bcrypt($validated['password']),
    ]);

    return redirect()->route('users.index')->with('success', 'Usuario creado con éxito.');
}

    public function create()
    {
        return view('users.create_edit');
    }

    public function edit(User $user)
    {
        return view('users.create_edit', compact('user'));
    }

    public function update(StoreUserRequest $request, User $user)
    {
        $user->update($request->validated());
        return redirect()->route('users.index')->with('success', 'Usuario actualizado con éxito.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado con éxito.');
    }
}
