<?php

use App\Http\Controllers\OrderLineController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/sanctum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        // 'device_name' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    return $user->createToken($request->password)->plainTextToken;
});

Route::middleware('auth:sanctum')->resource('salesOrder', SalesOrderController::class, [
    'except' => ['create', 'edit']
]);

Route::middleware('auth:sanctum')->resource('orderLine', OrderLineController::class, [
    'except' => ['create', 'edit', 'store', 'update']
]);

Route::middleware('auth:sanctum')->resource('users', UserController::class, [
    'except' => ['create', 'edit']
]);

Route::post('/bulkStore', [SalesOrderController::class, 'bulkStore']);
Route::get('/getSalesByReps', [SalesOrderController::class, 'getSalesByReps']);
