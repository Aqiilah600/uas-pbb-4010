<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Data Mahasiswa
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium">Daftar Mahasiswa</h3>
                    <a href="{{ route('mahasiswa.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        + Tambah Mahasiswa
                    </a>
                </div>

                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="text-left text-sm font-semibold text-gray-600">
                            <th class="px-4 py-2">Foto</th>
                            <th class="px-4 py-2">Nama Lengkap</th>
                            <th class="px-4 py-2">NIM</th>
                            <th class="px-4 py-2">Jenis Kelamin</th>
                            <th class="px-4 py-2">Angkatan</th>
                            <th class="px-4 py-2">Hobi</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($mahasiswas as $mhs)
                            <tr class="text-sm">
                                <td class="px-4 py-2">
                                    @if ($mhs->foto_profil)
                                        <img src="{{ asset('storage/' . $mhs->foto_profil) }}" class="w-10 h-10 rounded-full object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gray-200"></div>
                                    @endif
                                </td>
                                <td class="px-4 py-2">{{ $mhs->nama_lengkap }}</td>
                                <td class="px-4 py-2">{{ $mhs->nim }}</td>
                                <td class="px-4 py-2">{{ $mhs->jenis_kelamin }}</td>
                                <td class="px-4 py-2">{{ $mhs->angkatan }}</td>
                                <td class="px-4 py-2">{{ $mhs->hobis->pluck('nama_hobi')->join(', ') }}</td>
                                <td class="px-4 py-2 space-x-2">
                                    <a href="{{ route('mahasiswa.edit', $mhs) }}" class="text-blue-600 hover:underline">Edit</a>
                                    <form action="{{ route('mahasiswa.destroy', $mhs) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-4 text-center text-gray-500">Belum ada data mahasiswa.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>