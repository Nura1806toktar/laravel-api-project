<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Sale;

class SalesController extends Controller
{
    /**
     * Sales API-мен жұмыс істеу әдісі.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $dateFrom = $request->query('dateFrom', '2025-01-01');
        $dateTo = $request->query('dateTo', '2025-01-31');
        $apiUrl = 'http://89.108.115.241:6969/api/sales';

        try {
            // API-ға сұрау жіберу (60 сек таймаут қостым)
            $response = Http::timeout(60)->get($apiUrl, [
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo,
                'key' => env('API_SECRET_KEY'),
            ]);

            if ($response->successful() && isset($response->json()['data'])) {
                $data = $response->json()['data'];

                Log::info('API response received', ['total_records' => count($data)]);

                foreach ($data as $sale) {
                    Sale::updateOrCreate(
                        ['g_number' => $sale['g_number']],
                        [
                            'date' => $sale['date'],
                            'last_change_date' => $sale['last_change_date'],
                            'supplier_article' => $sale['supplier_article'],
                            'tech_size' => $sale['tech_size'],
                            'barcode' => $sale['barcode'],
                            'total_price' => $sale['total_price'],
                            'discount_percent' => $sale['discount_percent'],
                            'is_supply' => $sale['is_supply'],
                            'is_realization' => $sale['is_realization'],
                            'promo_code_discount' => $sale['promo_code_discount'],
                            'warehouse_name' => $sale['warehouse_name'],
                            'country_name' => $sale['country_name'],
                            'oblast_okrug_name' => $sale['oblast_okrug_name'],
                            'region_name' => $sale['region_name'],
                            'income_id' => $sale['income_id'],
                            'sale_id' => $sale['sale_id'],
                            'odid' => $sale['odid'],
                            'spp' => $sale['spp'],
                            'for_pay' => $sale['for_pay'],
                            'finished_price' => $sale['finished_price'],
                            'price_with_disc' => $sale['price_with_disc'],
                            'nm_id' => $sale['nm_id'],
                            'subject' => $sale['subject'],
                            'category' => $sale['category'],
                            'brand' => $sale['brand'],
                            'is_storno' => $sale['is_storno'],
                        ]
                    );
                }

                return response()->json([
                    'message' => 'Sales data saved successfully.',
                    'total_saved' => count($data),
                ]);
            } else {
                Log::error('API response error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return response()->json([
                    'error' => 'Failed to fetch or process sales data',
                    'status' => $response->status(),
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Unexpected error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'An unexpected error occurred',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
