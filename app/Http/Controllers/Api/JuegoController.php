<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JuegoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    //GET
    public function index()
    {
        $query = Juego::with(['plataforma', 'generos']) ->where('activo', true);

        if ($request->has('buscar')) {
            $termino = $request->input('buscar');
            $query->whereHas('titulo', 'LIKE', '%' . $termino .'%');
                $q->where('slug', $plataformaSlug);
            };

        $juegos = $query->orderBy('created_at', 'desc')->get(); 
        
        return response()->json([
            'success' => true,
            'data' => $juegos->count(),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */

    //POST
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion_corta' => 'nullable|string',
            'descripcion_larga' => 'nullable|string',
            'precio_normal' => 'required|numeric|',
            'precio_oferta' => 'nullable|numeric|lt:precio_normal',
            'imagen_url' => 'nullable|string',
            'destacada' => 'boolean',
            'activo' => 'boolean',
            'plataforma_id' => 'required|exists:plataformas,id'
            'generos' => 'array',
            'generos.*' => 'exists:generos,id'
        ]);
    
        $juego = Juego::create($request->all());

        if ($request->has('generos')) {
            $juego->generos()->sync($request->input('generos'));

        return response()->json([
            'success' => true,
            'message' => 'Juego creado correctamente',
            'data' => $juego -> load('generos')
            ], 201);
        }
      
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $juego = Juego::with(['plataforma', 'generos'])->where('activo', true)->find($id);

        if (!$juego) {
            return response()->json([
                'success' => false,
                'message' => 'Juego no encontrado'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $juego
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $juego = Juego::find($id);

        if (!$juego) {
            return response()->json([
                'success' => false,
                'message' => 'Juego no encontrado'
            ], 404);
        }

        $request->validate([
            'titulo' => 'sometimes|requiered|string|max:255',
            'descripcion_corta' => 'sometimes|nullable|string',
            'descripcion_larga' => 'sometimes|nullable|string',
            'precio_normal' => 'sometimes|requiered|numeric|',
            'precio_oferta' => 'sometimes|nullable|numeric|lt:precio_normal',
            'imagen_url' => 'sometimes|nullable|string',
            'destacada' => 'sometimes|boolean',
            'activo' => 'sometimes|boolean',
            'plataforma_id' => 'sometimes|requiered|exists:plataformas,id',
            'generos' => 'sometimes|array',
            'generos.*' => 'exists:generos,id'
        ]);

        $juego->update($request->all());
        if ($request->has('generos')) {
            $juego->generos()->sync($request->input('generos'));
        }
        return response()->json([
            'success' => true,
            'message' => 'Juego actualizado correctamente',
            'data' => $juego->load('generos')
        ], 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $juego = Juego::find($id);

        if (!$juego) {
            return response()->json([
                'success' => false,
                'message' => 'Juego no encontrado'
            ], 404);
        }

        $juego->delete();

        return response()->json([
            'success' => true,
            'message' => 'Juego eliminado correctamente'
        ], 200);
    }
}
