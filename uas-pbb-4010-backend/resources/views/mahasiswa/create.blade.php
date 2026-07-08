<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Data Mahasiswa
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-6">

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('mahasiswa.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="silahkan masukan nama">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nomor Induk Mahasiswa</label>
                        <input type="text" name="nim" value="{{ old('nim') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="silahkan masukan nim">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                        <div class="mt-1 space-x-4">
                            <label><input type="radio" name="jenis_kelamin" value="L" {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }}> L</label>
                            <label><input type="radio" name="jenis_kelamin" value="P" {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }}> P</label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Foto Profil</label>
                        <input type="file" name="foto_profil" class="mt-1 block w-full">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Angkatan Mahasiswa</label>
                        <input type="text" name="angkatan" value="{{ old('angkatan') }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="contoh: 2023">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Hobby</label>
                        <div class="mt-1 space-x-4">
                            @foreach ($hobis as $hobi)
                                <label>
                                    <input type="checkbox" name="hobi_ids[]" value="{{ $hobi->id }}">
                                    {{ $hobi->nama_hobi }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex space-x-2 pt-4">
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                            Kirim
                        </button>
                        <a href="{{ route('mahasiswa.index') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>