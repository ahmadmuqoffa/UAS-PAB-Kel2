<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register_admin(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|string|email|unique:users',
        'password' => 'required|confirmed|min:8',
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'email_verified_at' => now(),
        'password' => bcrypt($request->password),
        'role' => 'ADMINISTRATOR',
        'plain_token' => '',
    ]);

    return response()->json(['message' => 'Admin berhasil didaftarkan'], 200);
}

public function register_terminal(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|string|email|unique:users',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'email_verified_at' => now(),
        'password' => bcrypt('12345678'),
        'role' => 'TERMINAL',
        'plain_token' => '', // Tambahkan nilai ini
    ]);
    

    $plain_token = $user->createToken('Machine-to-Machine Token')->plainTextToken;
    $user->plain_token = $plain_token;
    $user->save();

    return response()->json([
        'message' => 'Terminal berhasil didaftarkan',
        'token' => $plain_token,
    ], 200);
}


public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid login credentials'], 401);
    }

    $token = $user->createToken('API Token')->plainTextToken;

    return response()->json([
        'message' => 'Login successful',
        'token' => $token,
    ]);
}

public function logout(Request $request)
{
    $request->user()->currentAccessToken()->delete();
    return response()->json(['message' => 'Logged out successfully']);
}

public function terminal_token(Request $request)
{
    $email = $request->get('email', '');
    $user = User::where('email', $email)->where('role', 'TERMINAL')->first();

    if ($user == null) {
        return response()->json([
            'message' => 'Data tidak ada',
            'email' => $email,
        ], 404);
    } else {
        return response()->json([
            'message' => 'Berhasil',
            'token' => $user->plain_token,
            'email' => $email,
        ], 200);
    }
}

public function list(Request $request)
{
    $page = $request->input('page', 0);
    $page_size = $request->input('page_size', 10);

    return response()->json([
        'message' => 'Berhasil',
        'users' => User::skip($page * $page_size)->take($page_size)
            ->select('id', 'name', 'email', 'role')->get(),
    ], 200);
}


}
