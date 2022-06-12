<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use App\Models\User;
use Auth;
use Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->with('provinsi', 'kabupaten', 'kecamatan')->get();
        return response()->json(['data' => $users, 'message' => 'Users fetched.']);
    }

    public function show($id)
    {
        $user = User::where('id', $id)->with('provinsi', 'kabupaten', 'kecamatan')->firstOrFail();
        if (is_null($user)) {
            return response()->json('Data not found', 404); 
        }
        return response()->json(['data' => $user, 'message' => 'Users fetched.']);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'phone_number' => 'required|string|max:13',
            'address' => 'required|string',
            'provinsi_id' => 'numeric',
            'kabupaten_id' => 'numeric',
            'kecamatan_id' => 'numeric',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if($validator->fails()){
            return response()->json(['message' => collect($validator->errors()->all())->implode(';')], 422);     
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->address = $request->address;
        $user->provinsi_id = $request->provinsi_id;
        $user->kabupaten_id = $request->kabupaten_id;
        $user->kecamatan_id = $request->kecamatan_id;
        

        if ($request->hasfile('image')) {
            File::deleteDirectory(public_path('/storage/user/'.$user->id));
            $avatar = $request->file('image');
            $path = $avatar->storeAs('user/'.$user->id, "avatar.".$avatar->extension(), 'public');
            $imagePath = '/storage/'.$path;
            $user->avatar_url = $imagePath;
        }
        
        $user->save();
        
        return response()->json(['data' => $user, 'message' => 'User updated successfully.']);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['data' => $user, 'message' => 'User deleted successfully']);
    }
}
