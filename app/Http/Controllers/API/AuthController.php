<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
use Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'required|string|max:13',
            'address' => 'required|string',
            'provinsi_id' => 'numeric',
            'kabupaten_id' => 'numeric',
            'kecamatan_id' => 'numeric',
            'password' => 'required|string|min:8'
        ]);

        if($validator->fails()){
            return response()->json(['message' => collect($validator->errors()->all())->implode(';')], 422);       
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'provinsi_id' => $request->provinsi_id,
            'kabupaten_id' => $request->kabupaten_id,
            'kecamatan_id' => $request->kecamatan_id,
            'avatar_url' => "",
            'password' => Hash::make($request->password)
         ]);

        return response()
            ->json(['data' => $user, 'message' => 'Register success.']);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password')))
        {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::where('email', $request['email'])->with('provinsi', 'kabupaten', 'kecamatan')->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['data' => $user, 'access_token' => $token, 'token_type' => 'Bearer', 'message' => 'Login success.']);
    }

    // method for user logout and delete token
    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();;
        return response()
        ->json(['message' => 'You have successfully logged out and the token was successfully deleted']);
    }
}
