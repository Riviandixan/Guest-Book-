<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'kouta',
        'status',
        'start_time',
        'end_time'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($room) {
            if (!$room->status) {
                $room->status = 'pending';
            }
        });
    }
}
