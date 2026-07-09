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
        // 1. Longgarkan validasi hobi_ids agar menerima teks pecahan dari FormData / Postman
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nim' => 'required|string|unique:mahasiswas,nim',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'foto_profil' => 'nullable|image|max:5120',
            'angkatan' => 'required|string|max:10',
            'hobi_ids' => 'nullable', // <-- Dibuat fleksibel (Hapus kata |array)
        ]);

        if ($request->hasFile('foto_profil')) {
            $validated['foto_profil'] = $request->file('foto_profil')->store('foto-profil', 'public');
        }

        // 2. Simpan data mahasiswa ke database
        $mahasiswa = Mahasiswa::create($validated);

        // 3. Amankan data hobi_ids agar dikonversi paksa menjadi array bersih sebelum masuk tabel pivot
        if ($request->has('hobi_ids') && !empty($request->hobi_ids)) {
            $hobiIds = $request->hobi_ids;
            
            // Jika dikirim dari postman/flutter berupa string (misal: "1,2,3") kita pecah jadi array
            if (is_string($hobiIds)) {
                $hobiIds = explode(',', $hobiIds);
            }
            
            // Sinkronisasi ke tabel relasi mahasiswa_hobi
            $mahasiswa->hobis()->sync($hobiIds);
        }

        $mahasiswa->load('hobis');

        // 4. Kembalikan response JSON sukses asli
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
            'hobi_ids' => 'nullable',
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