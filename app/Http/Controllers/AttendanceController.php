<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function checkIn(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $alreadyCheckedIn = Attendance::where('user_id', $request->user()->id)
            ->whereDate('check_in', now()->toDateString())
            ->exists();


        if ($alreadyCheckedIn) {
            return response()->json(['message' => 'Already checked in today'], 400);
        }

        $attendance = Attendance::create([
            'user_id' => $request->user()->id,
            'check_in' => now(),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json(['message' => 'Checked in successfully', 'date' => $attendance], 200);
    }

    public function checkOut(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $attendance = Attendance::where('user_id', $request->user()->id)
            ->whereDate('check_in', now()->toDateString())
            ->whereNull('check_out')
            ->first();

        if (!$attendance) {
            return response()->json(['message' => 'Not checked in or already checked out'], 400);
        }

        $attendance->update([
            'check_out' => now(),
        ]);

        return response()->json(['message' => 'Checked out successfully', 'date' => $attendance], 200);
    }

    public function history(Request $request)
    {
        $history = Attendance::where('user_id', auth()->id())
            ->orderBy('check_in', 'desc')
            ->get();
        return response()->json($history, 200);
    }
}
