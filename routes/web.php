<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('app');
});

// Catch-all route for Vue Router
// This should be the last route defined in this file
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
