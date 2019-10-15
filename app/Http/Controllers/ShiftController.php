<?php

namespace App\Http\Controllers;

use App\Movement;
use App\Order;
use App\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ShiftController extends BaseController
{
    public function index()
    {
        $todayShift = Shift::whereNull('end_time')->orderBy('id', 'desc')->first();
        $shifts = Shift::orderBy('id', 'desc')->paginate(10);
        return view('admin.shifts', compact('shifts'))
            ->with(['todayShift' => $todayShift]);
    }


    public function store(Request $request)
    {

        if ($request->shift == 'on') {
            $shift = new Shift();
            $shift->start_time = now();
            $shift->user_open = Auth::id();
            $shift->save();

        } else {
            $shift = Shift::whereNull('end_time')->orderBy('id', 'desc')->first();
            $interval = 7;
            if ($shift->start_time->addHours($interval) < now()) {
                $shift->end_time = now();
                $shift->user_close = Auth::id();
                $shift->update();
                return redirect()->route('shifts');

            }
            return redirect()
                ->route('shifts')
                ->with(['error' => "You cannot close shift before $interval hours"]);
        }
        return redirect()->route('shifts');
    }
}
