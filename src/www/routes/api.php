<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Expenses\ExpensesController;
use App\Http\Controllers\Api\Expenses\PaymentMethodController;
use App\Http\Controllers\Api\Expenses\CategoryController;

// 支出監理APIルート
Route::apiResource('expenses', ExpensesController::class);

// 支払い種類管理APIルート
Route::apiResource('payment-methods', PaymentMethodController::class);

// 支払いカテゴリ管理APIルート
Route::apiResource('categories', CategoryController::class);
