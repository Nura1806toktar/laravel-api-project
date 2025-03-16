<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class IncomesController extends Controller
{
    public function index(Request $request)
    {
        // Параметрлерді сұраудан алу
        $dateFrom = $request->query('dateFrom');
        $dateTo = $request->query('dateTo');

        // Қашықтағы API-ға сұрау жіберу
        $response = Http::get('http://89.108.115.241:6969/api/incomes', [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'key' => env('API_SECRET_KEY'),
        ]);

        // Жауапты тексеру және JSON-ды қайтару
        if ($response->successful()) {
            return $response->json(); // JSON форматында жауап беру
        }

        return response()->json(['error' => 'Failed to fetch incomes'], 500); // Қате болса
    }
}

