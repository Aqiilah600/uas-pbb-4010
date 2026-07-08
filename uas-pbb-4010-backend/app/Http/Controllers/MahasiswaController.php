<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\Hobi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswas = Mahasiswa::with('hobis')->latest()->get();
        return view('mahasiswa.index', compact('mahasiswas'));
    }

    public function create()
    {
        $hobis = Hobi::all();
        return view('mahasiswa.create', compact('hobis'));
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

        return redirect()->route('mahasiswa.index')->with('success', 'Data mahasiswa berhasil ditambahkan.');
    }

    public function show(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load('hobis');
        return view('mahasiswa.show', compact('mahasiswa'));
    }

    public function edit(Mahasiswa $mahasiswa)
    {
        $hobis = Hobi::all();
        $mahasiswa->load('hobis');
        return view('mahasiswa.edit', compact('mahasiswa', 'hobis'));
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

        return redirect()->route('mahasiswa.index')->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy(Mahasiswa $mahasiswa)
    {
        if ($mahasiswa->foto_profil) {
            Storage::disk('public')->delete($mahasiswa->foto_profil);
        }
        $mahasiswa->delete();

        return redirect()->route('mahasiswa.index')->with('success', 'Data mahasiswa berhasil dihapus.');
    }
}