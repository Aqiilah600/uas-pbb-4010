<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nim' => 'required|string|unique:mahasiswas,nim',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'foto_profil' => 'nullable|image|max:5120',
            'angkatan' => 'required|string|max:10',
            'hobi_ids' => 'nullable|array',
        ]);

        if ($request->hasFile('foto_profil')) {
            $validated['foto_profil'] = $request->file('foto_profil')->store('foto-profil', 'public');
        }

        $mahasiswa = Mahasiswa::create($validated);
        $mahasiswa->hobis()->sync($request->input('hobi_ids', []));
        $mahasiswa->load('hobis');

        return response()->json([
            'message' => 'Data mahasiswa berhasil ditambahkan.',
            'data' => $mahasiswa,
        ], 201);
    }

    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nim' => 'required|string|unique:mahasiswas,nim,' . $mahasiswa->id,
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'foto_profil' => 'nullable|image|max:5120',
            'angkatan' => 'required|string|max:10',
            'hobi_ids' => 'nullable|array',
        ]);

        if ($request->hasFile('foto_profil')) {
            if ($mahasiswa->foto_profil) {
                Storage::disk('public')->delete($mahasiswa->foto_profil);
            }
            $validated['foto_profil'] = $request->file('foto_profil')->store('foto-profil', 'public');
        }

        $mahasiswa->update($validated);
        $mahasiswa->hobis()->sync($request->input('hobi_ids', []));
        $mahasiswa->load('hobis');

        return response()->json([
            'message' => 'Data mahasiswa berhasil diperbarui.',
            'data' => $mahasiswa,
        ]);
    }

public function destroy(Mahasiswa $mahasiswa)
    {
        if ($mahasiswa->foto_profil) {
            Storage::disk('public')->delete($mahasiswa->foto_profil);
        }
        $mahasiswa->delete();

        return response()->json([
            'message' => 'Data mahasiswa berhasil dihapus.',
        ]);
    }
}