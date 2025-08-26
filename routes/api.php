<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChatBotController;
use App\Http\Controllers\Api\ChatBotUcmController;
use App\Http\Controllers\Api\OptionController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/', [UserController::class, 'store']);
    Route::get('{user}', [UserController::class, 'show']);
    Route::put('{user}', [UserController::class, 'update']);
    Route::delete('{user}', [UserController::class, 'destroy']);

    Route::post('{user}/roles', [UserController::class, 'assignRoles']);
    Route::post('{user}/permissions', [UserController::class, 'givePermissions']);
});

Route::prefix('roles')->group(function () {
    Route::get('/', [RoleController::class, 'index']);
    Route::post('/', [RoleController::class, 'store']);
    Route::get('{role}', [RoleController::class, 'show']);
    Route::put('{role}', [RoleController::class, 'update']);
    Route::delete('{role}', [RoleController::class, 'destroy']);
});

Route::prefix('permissions')->group(function () {
    Route::get('/', [PermissionController::class, 'index']);
    Route::post('/', [PermissionController::class, 'store']);
    Route::get('{permission}', [PermissionController::class, 'show']);
    Route::put('{permission}', [PermissionController::class, 'update']);
    Route::delete('{permission}', [PermissionController::class, 'destroy']);
});

Route::get('/whatsapp/{number}', [ChatBotController::class, 'sendwhatsapp']);

// Route::get('/webhook', [ChatBotController::class, 'getwebhook']);
// // Route::post('/webhook', [ChatBotController::class, 'postwebhook']); old

// Route::post('/webhook', [ChatBotController::class, 'handleWebhook']);
// Route::post('/chatbot/answer', [ChatBotController::class, 'handleAnswer']); old


Route::post('login',[AuthController::class,'login']);




Route::get('/questions', [QuestionController::class, 'index']);
Route::get('/questions/{id}', [QuestionController::class, 'show']);
Route::post('/questions', [QuestionController::class, 'store']);
Route::put('/questions/{id}', [QuestionController::class, 'update']);
Route::delete('/questions/{id}', [QuestionController::class, 'destroy']);

Route::get('/options', [OptionController::class, 'index']);
Route::get('/options/{id}', [OptionController::class, 'show']);
Route::post('/options', [OptionController::class, 'store']);
Route::put('/options/{id}', [OptionController::class, 'update']);
Route::delete('/options/{id}', [OptionController::class, 'destroy']);

// Para pegar opções de uma pergunta específica:
Route::get('/questions/{id}/options', [OptionController::class, 'indexByQuestion']);

Route::get('/webhook', [ChatBotUcmController::class, 'getwebhook']);

Route::post('/webhook', [ChatBotUcmController::class, 'handleWebhook']);