<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\PaymentMethodController;
use App\Http\Controllers\Api\CategoryController;

// 支出監理APIルート
Route::apiResource('expenses', ExpenseController::class);
Route::get('/expenses-summary-monthly', [ExpenseController::class, 'monthlySummary']);

// 支払い種類管理APIルート
Route::apiResource('payment-methods', PaymentMethodController::class)->only(['index']);

// 支払いカテゴリ管理APIルート
Route::apiResource('categories', CategoryController::class)->only(['index']);
