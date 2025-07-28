<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PerfilController extends Controller
{
    public function index()
    {
        return view('perfil.index');
    }

    public function store(Request $request)
    {
        //Modificar el Request
        $request->request->add(['username' => Str::slug($request->username)]);
        
        $request->validate([
            'username' => ['required', Rule::unique('users','username')->ignore(Auth::user()->id), 'min:3', 'max:20', 'not_in:twitter,editar-perfil']
        ]);

        if($request->imagen) {
           // Importar la librería de Gd usando el Driver
            $manager = new ImageManager(new Driver());
            
            // Leer el archivo enviado desde el formulario
            $imagen = $request->file('imagen');

            // Generar un id único para las imagenes
            $nombreImagen = Str::uuid() . "." . $imagen->extension();

            // Guardar la imagen al servidor
            $imagenServidor = $manager->read($imagen);

            // Redimencionamos la imagen con intervention
            $imagenServidor->scale(1000, 1000);

            // Agregamos la imagen a la  carpeta en public donde se guardaran las imagenes
            $imagenesPath = public_path('perfiles') . '/' . $nombreImagen;
            
            // Una vez procesada la imagen entonces guardamos la imagen en la carpeta que creamos
            $imagenServidor->save($imagenesPath);
        }

        //Guardar cambios
        $usuario = User::find(Auth::user()->id);
        $usuario->username = $request->username;
        $usuario->imagen = $nombreImagen ?? Auth::user()->imagen ?? null;
        $usuario->save();

        //Redireccionar al Usuario
        return redirect()->route('posts.index', $usuario->username);
    }
}
