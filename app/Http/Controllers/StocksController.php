<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StocksController extends Controller
{
    public function index(Request $request)
    {
        // Ағымдағы күнді алу
        $dateFrom = now()->format('Y-m-d'); // Тек ағымдағы күн

        // Қашықтағы API-ға сұрау жіберу
        $response = Http::get('http://89.108.115.241:6969/api/stocks', [
            'dateFrom' => $dateFrom,
            'key' => env('API_SECRET_KEY'),
        ]);

        // Жауапты тексеру және қайтару
        if ($response->successful()) {
            return $response->json(); // JSON жауапты қайтару
        }

        return response()->json(['error' => 'Failed to fetch stocks'], 500); // Қате жағдайда жауап
    }
}
