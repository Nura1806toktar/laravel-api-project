<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        // Параметрлерді сұраудан алу
        $dateFrom = $request->query('dateFrom');
        $dateTo = $request->query('dateTo');
        $page = $request->query('page', 1); // Әдепкі бет: 1
        $limit = $request->query('limit', 500); // Әдепкі лимит: 500

        // Қашықтағы API-ға сұрау жіберу
        $response = Http::get('http://89.108.115.241:6969/api/orders', [
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'page' => $page,
            'limit' => $limit,
            'key' => env('API_SECRET_KEY'),
        ]);

        // Жауапты тексеру және қайтару
        if ($response->successful()) {
            return $response->json(); // JSON форматында жауап беру
        }

        return response()->json(['error' => 'Failed to fetch orders'], 500); // Қате жағдайда жауап
    }
}


