<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Time extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'start_time',
        'end_time',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($time) {
            $room = $time->room;
            if (!$room->start_time || $room->start_time > $time->start_time) {
                $room->start_time = $time->start_time;
            }

            if (!$room->end_time || $room->end_time < $time->end_time) {
                $room->end_time = $time->end_time;
            }

            $room->save();
        });
    }
}
