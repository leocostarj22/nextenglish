<?php

use Illuminate\Support\Facades\Route;

$serveSpa = function () {
    $path = public_path('app/index.html');

    if (! file_exists($path)) {
        return view('welcome');
    }

    return response()->file($path);
};

Route::get('/', $serveSpa);

Route::get('/{any}', $serveSpa)->where('any', '^(?!api).*');
