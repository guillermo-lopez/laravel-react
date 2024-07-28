<?php

use Illuminate\Support\Facades\Route;

Route::get('/{path?}', function () {
    return view('app', ['Laravel' => app()->version()]);
})->where('path', '.*')->name('app');


require __DIR__.'/auth.php';
