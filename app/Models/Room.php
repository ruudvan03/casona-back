<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Room extends Model
{
    // Asegúrate de incluir todos los campos que se llenan desde el formulario
    protected $fillable = [
        'name', 
        'slug', 
        'description', 
        'price_per_night', 
        'capacity', 
        'is_active'
    ];

    /**
     * Relación: Una habitación tiene muchas fotos en su galería.
     * Usamos HasMany para que coincida con la tabla 'room_images' que creamos.
     */
    public function images(): HasMany
    {
        return $this->hasMany(RoomImage::class);
    }

    /**
     * Relación: Una habitación tiene muchas reservas.
     * Mantenemos MorphMany si tu sistema de reservas es polimórfico 
     * (es decir, si la Palapa también usa la misma tabla de Bookings).
     */
    public function bookings(): MorphMany
    {
        return $this->morphMany(Booking::class, 'bookable');
    }
}