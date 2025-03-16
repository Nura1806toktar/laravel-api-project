<?php

use Illuminate\Support\Facades\Route;

Route::get('/tests', function () {
    return response()->json(['message' => 'API endpoint is working!']);
});
