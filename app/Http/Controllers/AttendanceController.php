<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

const OFFICE_LAT = -6.210656810621744;
const OFFICE_LNG = 106.81294239422382;
const MAX_DISTANCE = 100; // meters

class AttendanceController extends Controller
{


    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371000; // dalam meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return $distance;
    }


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


        $distance = $this->calculateDistance(
            $request->latitude,
            $request->longitude,
            OFFICE_LAT,
            OFFICE_LNG
        );


        if ($distance > MAX_DISTANCE) {
            return response()->json(['message' => 'You are too far from the office (' .  round($distance) .  ' meter',], 400);
        }


        $attendance = Attendance::create([
            'user_id' => $request->user()->id,
            'check_in' => now(),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json(['message' => 'Checked in successfully (' . round($distance) . ' meter)', 'date' => $attendance], 200);
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
        $history = Attendance::where('user_id', auth()->id)
            ->orderBy('check_in', 'desc')
            ->get();
        return response()->json($history, 200);
    }
}
