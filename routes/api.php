<?php

use App\Http\Controllers\GetAnswer;
use Illuminate\Support\Facades\Route;

Route::get('get-answer', [GetAnswer::class, 'getForm']);
Route::get('get-answer-curl', [GetAnswer::class, 'getFormWithCurl']);

