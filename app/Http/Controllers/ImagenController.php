<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImagenController extends Controller
{
    public function store(Request $request)
    {
        // Importar la librería de Gd usando el Driver
        $manager = new ImageManager(new Driver());
        
        // Leer el archivo enviado desde el formulario
        $imagen = $request->file('file');

        // Generar un id único para las imagenes
        $nombreImagen = Str::uuid() . "." . $imagen->extension();

        // Guardar la imagen al servidor
        $imagenServidor = $manager->read($imagen);

        // Redimencionamos la imagen con intervention
        $imagenServidor->scale(1000, 1000);

        // Agregamos la imagen a la  carpeta en public donde se guardaran las imagenes
        $imagenesPath = public_path('uploads') . '/' . $nombreImagen;
        
        // Una vez procesada la imagen entonces guardamos la imagen en la carpeta que creamos
        $imagenServidor->save($imagenesPath);

        // Retornamos el nombre de la imagen, que es el nombre que nos da el ID único con uuid()
        return response()->json(['imagen' => $nombreImagen]);
    }
}
