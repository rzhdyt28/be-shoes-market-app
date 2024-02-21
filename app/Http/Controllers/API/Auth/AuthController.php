<?php

namespace App\Http\Controllers\API\Auth;

use App\Hellper\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Rules\Password;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{
    public function register(Request $request) {

        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'username' =>  ['required', 'string', 'max:255', 'unique:users'],
                'email' => ['required', 'string', 'email', 'unique:users'],
                'phone' => ['nullable', 'string',],
                'password' => ['required', 'string', new Password],
            ]);

            // create user
            User::create([
                'name' =>  $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);
            $user = User::where('email', $request->email)->first();

            $token = Auth::login($user);

            return ResponseFormatter::success([
                # code here ...
                'token' => $token,
                'type' => 'Bearer',
                'user' => $user,
            ], 'User Registered');

        } catch (Exception $err) {

            return ResponseFormatter::error([
                # code here ...
                'message' => 'Something Went Wrong!!',
                'error' => $err,
            ], 'Authentication', 500);
        }
    }

    public function login(Request $request) {
        try {
            $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);

            $credentials = request(['email',  'password']);

            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'meta' => [
                        'code' => 401,
                        'message' => 'Unauthorized',
                    ]
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            if (!Hash::check($request->password, $user->password, [])) {
                # code...
                throw new \Exception('Invalid Credentials');
            }

            return ResponseFormatter::success([
                # code here ...
                'token' => $token,
                'type' => 'Bearer',
                'user' => $user,
            ],  'Login Berhasil');

        } catch (Exception $err) {
            # code here ..
            return ResponseFormatter::error([
                # code here ...
                'message' => 'Something Went Wrong!!',
                'error' => $err,
            ], 'Authenticated', 500);
        }
    }

    public function checkUser(Request $request) {
        # code here ...
        if ($request->user()) {
            # code...
            return ResponseFormatter::success(auth()->user(), 'Data user berhasil diambil!');
        }
        return ResponseFormatter::error([],  'Anda tidak memiliki akses!', 401);
    }

    public function update(Request $request) {
        # code here ...
        $data = $request->all();

        $users = Auth::user();
        $users->update($data);

        return ResponseFormatter::success($users,'profile update');
    }

    public function logout(){
        # code here ...
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return ResponseFormatter::success([],'Logged out Successfully');
        } catch (\Throwable $th) {
            return ResponseFormatter::error([[], 'Token has expired'], $th);
        }
    }
}
