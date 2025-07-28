<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RegistrerController extends Controller
{
    public function index()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        //dd($request);
        //dd($request->get('name'));

        //Modificar el Request
        $request->request->add(['username' => Str::slug($request->username)]);

        //Validacion
        $request->validate([
            'name' => 'required|max:30',
            'username' => 'required|unique:users|min:3|max:30',
            'email' => 'required|unique:users|email|max:60',
            'password' => 'required|confirmed|min:8'
        ]);

       User::create([
        'name' => $request->name,
        'username' => $request->username,
        'email' => $request->email,
        'password' => $request->password
       ]);

       //Autenticar el usuario
       Auth::attempt(['email' => $request->email, 'password' => $request->password]);

       //Redireccionar
       return redirect()->route('posts.index');
    }
}
