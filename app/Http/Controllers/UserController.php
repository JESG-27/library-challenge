<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;

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
            'name' => $validated('name'),
            'email' => $validated('email'),
            'password' => $validated('password'),
        ]);

        return redirect('sucess', 'Usuario creado con éxito.');
    }
}
