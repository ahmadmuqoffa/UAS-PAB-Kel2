<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\MemberController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/login', [UserController::class, 'login']);
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('/user/register_admin', [UserController::class, 'register_admin']);
    Route::post('/user/register_terminal', [UserController::class, 'register_terminal']);
    Route::post('/logout', [UserController::class, 'logout']);
}); 
Route::middleware(['auth:sanctum', 'admin'])->post('/user/token', [UserController::class, 'terminal_token']);
Route::middleware(['auth:sanctum', 'admin'])->get('/user/list', [UserController::class, 'list']);
    
Route::middleware(['auth:sanctum', 'admin'])->post('/member/add', [MemberController::class, 'addMember']);
Route::middleware(['auth:sanctum', 'admin'])->get('/member/list', [MemberController::class, 'listMembers']);
Route::middleware(['auth:sanctum', 'admin'])->post('/facility/add', [MemberController::class, 'addFacility']);
Route::middleware(['auth:sanctum', 'admin'])->get('/facility/list', [MemberController::class, 'listFacility']);
Route::middleware(['auth:sanctum', 'terminal'])->post('/member/check-in', [MemberController::class, 'checkIn']);
Route::middleware(['auth:sanctum', 'terminal'])->post('/facility/access', [MemberController::class, 'accessFacility']);
Route::middleware(['auth:sanctum', 'terminal'])->post('/member/check-out', [MemberController::class, 'checkOut']);
Route::middleware(['auth:sanctum', 'terminal'])->get('/membership/status', [MemberController::class, 'membershipStatus']);