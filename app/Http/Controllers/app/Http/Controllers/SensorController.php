<?php

namespace App\Http\Controllers;

use App\Models\SensorData;
use App\Models\PumpStatus;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    // ESP32 kirim data sensor
    public function store(Request $request) {
        $data = SensorData::create([
            'temperature'   => $request->temperature,
            'humidity_air'  => $request->humidity_air,
            'humidity_soil' => $request->humidity_soil,
        ]);
        return response()->json(['message' => 'OK', 'data' => $data]);
    }

    // Dashboard ambil data terbaru
    public function latest() {
        $data = SensorData::latest()->first();
        return response()->json($data ?? [
            'temperature'   => 0,
            'humidity_air'  => 0,
            'humidity_soil' => 0,
        ]);
    }

    // Dashboard ambil history 10 data
    public function history() {
        $data = SensorData::latest()->take(10)->get();
        return response()->json($data);
    }

    // Ambil status pompa
    public function pumpStatus() {
        $pump = PumpStatus::latest()->first();
        return response()->json(['is_on' => $pump ? $pump->is_on : false]);
    }

    // Toggle pompa on/off
    public function togglePump() {
        $pump = PumpStatus::latest()->first();
        if ($pump) {
            $pump->update(['is_on' => !$pump->is_on]);
        } else {
            $pump = PumpStatus::create(['is_on' => true]);
        }
        return response()->json(['is_on' => $pump->is_on]);
    }
}