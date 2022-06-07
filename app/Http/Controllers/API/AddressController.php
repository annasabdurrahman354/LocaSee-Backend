<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Provinsi;
use App\Models\Kabupaten;
use App\Models\Kecamatan;
use Illuminate\Http\Request;
use Validator;

class AddressController extends Controller
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

    public function kabupaten()
    {
        $kabupatens = Kabupaten::orderBy('title')->get();
        return response()->json(['data' => $kabupatens, 'message' => 'Kabupatens fetched.']);
    }

    public function kabupatenByProvinsi($id)
    {
        $kabupatens = Kabupaten::where('provinsi_id', $id)->orderBy('title')->get();
        return response()->json(['data' => $kabupatens, 'message' => 'Kabupatens fetched.']);
    }

    public function kecamatan()
    {
        $kecamatans = Kecamatan::orderBy('title')->get();
        return response()->json(['data' => $kecamatans, 'message' => 'Kecamatans fetched.']);
    }

    public function kecamatanByKabupaten($id)
    {
        $kecamatans = Kecamatan::where('kabupaten_id', $id)->orderBy('title')->get();
        return response()->json(['data' => $kecamatans, 'message' => 'Kecamatans fetched.']);
    }
}
