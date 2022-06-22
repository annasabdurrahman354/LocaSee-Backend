<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Postesource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Validator;


class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->with('user', 'provinsi', 'kabupaten', 'kecamatan')->get();
        return response()->json(['data' => $posts, 'message' => 'Posts fetched.']);
    }

    public function show($id)
    {
        $post = Post::where('id', $id)->with('user', 'provinsi', 'kabupaten', 'kecamatan')->firstOrFail();
        if (is_null($post)) {
            return response()->json(['message' => 'Data not found'], 404); 
        }
        return response()->json(['data' => $post, 'message' => 'Posdddts fetched.']);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required|string|max:255',
            'desc' => 'required',
            'price' => 'required|numeric',
            'area' => 'required|numeric',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'rating_restaurant' => 'string',
            'user_id' => 'required|numeric',
            'provinsi_id' => 'required|numeric',
            'kabupaten_id' => 'required|numeric',
            'kecamatan_id' => 'required|numeric',
            'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if($validator->fails()){
            return response()->json(['message' => collect($validator->errors()->all())->implode(';')], 422);       
        }

        $allImagePath = [];

        $post = Post::create([
            'title' => $request->title,
            'desc' => $request->desc,
            'price' => $request->price,
            'area' => $request->area,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'rating_restaurant' => $request->rating_restaurant,
            'user_id' => $request->user_id,
            'provinsi_id' => $request->provinsi_id,
            'kabupaten_id' => $request->kabupaten_id,
            'kecamatan_id' => $request->kecamatan_id,
            'images' => $allImagePath,
        ]);

        if ($request->hasfile('images')) {
            $i = 0;
            $images = $request->file('images');
            foreach($images as $image) {
                $path = $image->storeAs('post/'.$post->id.'/images', $i.".".$image->extension(), 'public');
                $allImagePath[] = '/storage/'.$path;
                $i++;
            }
        }

        $post->images = $allImagePath;
        $post->save();

        return response()->json(['data' => $post, 'message' => 'Post created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $post  = Post::find($id);
        if (is_null($post)) {
            return response()->json(['message' => 'Data not found'], 404); 
        }

        $validator = Validator::make($request->all(),[
            'title' => 'required|string|max:255',
            'desc' => 'required',
            'price' => 'required|numeric',
            'area' => 'required|numeric',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'rating_restaurant' => 'string',
            'user_id' => 'required|numeric',
            'provinsi_id' => 'required|numeric',
            'kabupaten_id' => 'required|numeric',
            'kecamatan_id' => 'required|numeric',
            'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if($validator->fails()){
            return response()->json(['message' => collect($validator->errors()->all())->implode(';')], 422);       
        }

        $allImagePath = [];
        File::deleteDirectory(public_path('/storage/post/'.$post->id.'/images'));
        
        if ($request->hasfile('images')) {
            $i = 0;
            $images = $request->file('images');
            foreach($images as $image) {
                $path = $image->storeAs('post/'.$post->id.'/images', $i.".".$image->extension(), 'public');
                $allImagePath[] = '/storage/'.$path;
                $i++;
            }
        }

        $post->title = $request->title;
        $post->desc = $request->desc;
        $post->price = $request->price;
        $post->area = $request->area;
        $post->address = $request->address;
        $post->latitude = $request->latitude;
        $post->longitude = $request->longitude;
        $post->rating_restaurant = $request->rating_restaurant;
        $post->user_id = $request->user_id;
        $post->provinsi_id = $request->provinsi_id;
        $post->kabupaten_id = $request->kabupaten_id;
        $post->kecamatan_id = $request->kecamatan_id;
        $post->images = $allImagePath;
        $post->save();
        
        return response()->json(['data' => $post, 'message' => 'Post updated successfully.']);
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        if (is_null($post)) {
            return response()->json(['message' => 'Data not found'], 404); 
        }
        File::deleteDirectory(public_path('/storage/post/'.$post->id));
        $post->delete();
        return response()->json(['data' => $post, 'message' => 'Post deleted successfully.']);
    }

    public function getUserPosts($id)
    {
        $posts = Post::where('user_id', $id)->latest()->with('user', 'provinsi', 'kabupaten', 'kecamatan')->get();
        if(count($posts) === 0) {
            return response()->json('Data not found', 404); 
        }
        return response()->json(['data' => $posts, 'message' => 'Posts fetched.']);
    }

    public function filter(Request $request)
    {
        $posts = Post::latest()->with('user', 'provinsi', 'kabupaten', 'kecamatan');

        if ($request->has('search')){
            $posts->where('title', 'LIKE', "%{$request->search}%")
                    ->orWhere('desc', 'LIKE', "%{$request->search}%");
        }

        if ($request->has('price_min')) {
            $posts->where('price','>=', $request->price_min);
        }

        if ($request->has('price_max')) {
            $posts->where('price','<=', $request->price_max);
        }

        if ($request->has('area_min')) {
            $posts->where('area','>=', $request->area_min);
        }

        if ($request->has('area_max')) {
            $posts->where('area','<=', $request->area_max);
        }

        if ($request->has('distance') && $request->has('user_latitude') && $request->has('user_latitude')) {
            
        }

        if ($request->has('provinsi')) {
            $posts->where('provinsi_id', $request->provinsi);
        }

        if ($request->has('kabupaten')) {
            $posts->where('kabupaten_id', $request->kabupaten);
        }

        if ($request->has('kecamatan')) {
            $posts->where('kecamatan_id', $request->kecamatan);
        }
        
        return response()->json(['data' => $posts->get(), 'message' => 'Posts fetched.']);
    }
}
