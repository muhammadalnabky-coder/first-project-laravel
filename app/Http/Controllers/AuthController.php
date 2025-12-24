<?php

namespace App\Http\Controllers;

use App\Http\Requests\storeUserRequest;
use App\Http\Requests\updateProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function register(storeUserRequest $req, $roleId = 1)
    {
        try {
            $data = $req->validated();

            if ($req->hasFile('profile_image')) {

                $profileName = time() . '_profile_' . uniqid() . '.' .
                    $req->profile_image->getClientOriginalExtension();

                $req->profile_image->move(public_path('uploads/users'), $profileName);

                $data['profile_image'] = asset('uploads/users/' . $profileName);
            }

            if ($req->hasFile('id_image')) {

                $idName = time() . '_id_' . uniqid() . '.' .
                    $req->id_image->getClientOriginalExtension();

                $req->id_image->move(public_path('uploads/users'), $idName);

                $data['id_image'] =asset('uploads/users/' . $idName); ;
            }

            $data['role_id'] = $roleId;
            $data['password'] = Hash::make($data['password']);
            $data['remember_token'] = Str::random(10);

            $user = User::create($data);

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'status'  => true,
                'message' => 'User registered successfully',
                'user'    => $user,
                'token'   => $token,
            ], 201);

        } catch (\Exception $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Registration failed',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function updateProfile(updateProfileRequest $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($user->id!=auth()->id()) {
            return response()->json(['message' => 'Invalid, you are not the owner'], 400);
        }

        $request->validated();

        if ($request->filled('first_name')) {
            $user['first_name'] = $request->first_name;
        }

        if ($request->filled('last_name')) {
            $user['last_name']=$request->last_name;
        }

        if ($request->filled('birth_date')) {
            $user['birth_date']=$request->birth_date;
        }

        if ($request->hasFile('profile_image')) {
            $profileName = time() . '_profile_' . uniqid() . '.' .
                $request->profile_image->getClientOriginalExtension();

            $request->profile_image->move(public_path('uploads/users'), $profileName);

            $user['profile_image'] =asset('uploads/users/' . $profileName);
        }

        if ($request->hasFile('id_image')) {
            $idName = time() . '_id_' . uniqid() . '.' .
                $request->id_image->getClientOriginalExtension();

            $request->id_image->move(public_path('uploads/users'), $idName);

            $user['id_image'] =asset('uploads/users/' . $idName); ;
        }

        $user->save();

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user
        ]);
    }

    public function login(Request $req)
    {
        $credentials = $req->only('phone', 'password');
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid phone or password'
            ], 401);
        }

        return response()->json([
            'status' => true,
            'message' => 'Login successful',
            'token' => $token,
        ]);
    }

    public function logout()
    {
        try {
            JWTAuth::parseToken()->invalidate();

            return response()->json([
                'status'  => true,
                'message' => 'Logged out successfully'
            ]);

        } catch (JWTException $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Failed to logout, token invalid'
            ], 400);
        }
    }

    public function me()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            return response()->json([
                'status' => true,
                'user'   => $user
            ]);

        } catch (JWTException $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Token invalid or expired'
            ], 401);
        }
    }
}
