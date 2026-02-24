<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    /**
     * Muestra el listado de habitaciones con su galería cargada.
     */
    public function index()
    {
        // Eager loading para evitar el problema de consultas N+1
        $rooms = Room::with('images')->latest()->get();
        return view('admin.rooms.index', compact('rooms'));
    }

    /**
     * Muestra el formulario para crear una nueva habitación.
     */
    public function create()
    {
        return view('admin.rooms.create');
    }

    /**
     * Almacena una nueva habitación y su galería de imágenes.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price_per_night' => 'required|numeric',
            'capacity' => 'required|integer',
            'description' => 'nullable|string',
            'images' => 'required|array', 
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        // 1. Crear la habitación
        $room = Room::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'price_per_night' => $request->price_per_night,
            'capacity' => $request->capacity,
            'description' => $request->description,
        ]);

        // 2. Procesar y guardar cada imagen en la galería
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('rooms', 'public');
                
                RoomImage::create([
                    'room_id' => $room->id,
                    'path' => $path
                ]);
            }
        }

        return redirect()->route('rooms.index')->with('success', 'Habitación y galería creadas con éxito.');
    }

    /**
     * Muestra el formulario para editar.
     */
    public function edit(Room $room)
    {
        $room->load('images');
        return view('admin.rooms.edit', compact('room'));
    }

    /**
     * Actualiza los datos y añade más fotos a la galería sin borrar las anteriores.
     */
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price_per_night' => 'required|numeric',
            'capacity' => 'required|integer',
            'description' => 'nullable|string',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $room->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'price_per_night' => $request->price_per_night,
            'capacity' => $request->capacity,
            'description' => $request->description,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('rooms', 'public');
                
                RoomImage::create([
                    'room_id' => $room->id,
                    'path' => $path
                ]);
            }
        }

        return redirect()->route('rooms.index')->with('success', 'Habitación actualizada correctamente.');
    }

    /**
     * Elimina la habitación y todos sus archivos físicos.
     */
    public function destroy(Room $room)
    {
        foreach ($room->images as $image) {
            Storage::disk('public')->delete($image->path);
        }
        
        $room->delete();

        return redirect()->route('rooms.index')->with('success', 'Habitación y sus fotos eliminadas.');
    }

    /**
     * NUEVO: Eliminar una sola imagen de la galería.
     */
    public function deleteImage(RoomImage $image)
    {
        Storage::disk('public')->delete($image->path);
        $image->delete();

        return back()->with('success', 'Imagen eliminada de la galería.');
    }
}