<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Validator;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function provinsi()
    {
        $provinsis = Provinsi::orderBy('title')->get();
        return response()->json(['data' => $provinsis, 'message' => 'Provinsis fetched.']);
    }

    public function kabupaten(Request $request)
    {
        if ($request->has('provinsi')) {
            $kabupatens = Kabupaten::where('provinsi_id', $request->provinsi)->orderBy('title')->get();
            return response()->json(['data' => $kabupatens, 'message' => 'Kabupatens fetched.']);
        }
        $kabupatens = Kabupaten::orderBy('title')->get();
        return response()->json(['data' => $kabupatens, 'message' => 'Kabupatens fetched.']);
    }

    public function kecamatan(Request $request)
    {
        if ($request->has('kabupaten')) {
            $kecamatans = Kecamatan::where('kabupaten_id',$request->kabupaten)->orderBy('title')->get();
            return response()->json(['data' => $kecamatans, 'message' => 'Kecamatans fetched.']);
        }
        $kecamatans = Kecamatan::orderBy('title')->get();
        return response()->json(['data' => $kecamatans, 'message' => 'Kecamatans fetched.']);
    }
}
