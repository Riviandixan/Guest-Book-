<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;

class GuestController extends Controller
{
    public function index()
    {
        return view('guest.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:guests,name',
            'phone' => 'required',
            'purpose' => 'required',
        ]);

        $guest = new Guest();
        $guest->name = $request->name;
        $guest->phone = $request->phone;
        $guest->purpose = $request->purpose;
        $guest->save();

        session()->flash('success', 'Successfully created guest book!');

        return redirect()->back();
    }
}
