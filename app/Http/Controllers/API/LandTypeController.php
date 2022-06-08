<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\LandType;
use Illuminate\Http\Request;
use Validator;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types = LandType::orderBy('title')->get();
        return response()->json(['data' => $types, 'message' => 'Land types fetched.']);
    }
}
