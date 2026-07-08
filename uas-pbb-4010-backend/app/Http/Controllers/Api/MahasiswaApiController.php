<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaApiController extends Controller
{
    public function index()
    {
        $mahasiswas = Mahasiswa::with('hobis')->latest()->get();

        return response()->json([
            'data' => $mahasiswas,
        ]);
    }

    public function show(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load('hobis');

        return response()->json([
            'data' => $mahasiswa,
        ]);
    }
}