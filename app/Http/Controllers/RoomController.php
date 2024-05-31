<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Time;
use App\Models\Guest;
use App\Models\Booking;
use App\Rules\AvailableTime;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::all();


        return view('room.index', [
            'rooms' => $rooms
        ]);
    }

    public function ajax(Request $request)
    {
        $cari = $request->cari;

        $data = Room::query()->when($cari, function ($e, $cari) {
                $e->where('name', 'like', '%' . $cari . '%')->orWhere('description', 'like', '%' . $cari . '%');
            });

        return DataTables::eloquent($data)
            ->addColumn('aksi', function ($e) {

                if ($e->status == 'pending') {
                    return '<div class="btn-group float-end" role="group" aria-label="Button group with nested dropdown">
                            <div class="btn-group" role="group">
                                <button id="btn-pilih" data-room-id="' . $e->id . '" type="button" class="btn btn-primary"aria-expanded="false">
                                    Pilih
                                </button>
                            </div>
                        </div>';
                }
            })
            ->editColumn('image', function ($e) {
                return asset('/storage/' . $e->image);
            })
            ->rawColumns(['aksi', 'status'])
            ->make(true);
    }

    public function save(Request $request)
    {
        $roomId = $request->roomId;
        $startTime = $request->startTime;
        $endTime = $request->endTime;

        $guestId = Guest::orderBy('id', 'desc')->first();
        $room = Room::where('id', $roomId)->first();

        $request->validate([
            'startTime' => 'required|date',
            'endTime' => 'required|date|after:startTime'
        ]);

        if ($startTime < $room->start_time || $endTime > $room->end_time) {
            return response()->json(['message' => 'Waktu yang diminta tidak tersedia'], 400);
        }

        $booking = new Booking();
        $booking->room_id = $roomId;
        $booking->guest_id = $guestId->id;
        $booking->start_time = $startTime;
        $booking->end_time = $endTime;
        $booking->save();

        $room->status = 'booking';
        $room->save();

        return response()->json(['message' => 'Booking berhasil disimpan'], 200);
    }

}
