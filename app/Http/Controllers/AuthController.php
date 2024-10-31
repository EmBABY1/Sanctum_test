<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15|unique:users,phone',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'verification_code' => Str::random(6),
        ]);
        Log::info('Verification code for user ' . $user->phone . ' is ' . $user->verification_code);
        return response()->json([
            'user' => $user,
            'token' => $user->createToken('token')->plainTextToken,
        ]);
    }
    public function login(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('phone', $validated['phone'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if (!$user->is_verified) {
            return response()->json(['message' => 'Account not verified'], 403);
        }

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('authToken')->plainTextToken,
        ]);
    }
    public function verifyCode(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required|string',
            'verification_code' => 'required|string|size:6',
        ]);

        $user = User::where('phone', $validated['phone'])->first();

        if (!$user || $user->verification_code !== $validated['verification_code']) {
            return response()->json(['message' => 'Invalid verification code'], 400);
        }

        $user->is_verified = true;
        $user->save();

        return response()->json(['message' => 'Account verified']);
    }

}