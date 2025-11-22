<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;

// Public API routes

// Context update for favicon switching
Route::post('/set-context', function (Request $request) {
    $context = $request->input('context');
    if (in_array($context, ['unissa-cafe', 'tijarah'])) {
        session(['current_context' => $context]);
        return response()->json(['status' => 'success', 'context' => $context]);
    }
    return response()->json(['status' => 'error'], 400);
});
