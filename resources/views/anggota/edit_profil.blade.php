<x-app-sidebar>
    <x-slot name="header">Edit Profil</x-slot>
    <x-slot name="breadcrumb">Home / Profil / Edit</x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">

            @if(session('success'))
                <div class="bg-green-900 text-green-300 p-3 rounded mb-4 border border-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-900 text-red-300 p-3 rounded mb-4 border border-red-700">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('anggota.updateprofil') }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-400">Nama</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded p-2 text-white" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-400">Email</label>
                    <input type="email" value="{{ $user->email }}"
                        class="mt-1 block w-full bg-gray-600 border border-gray-600 rounded p-2 text-gray-400 cursor-not-allowed" disabled>
                    <p class="text-xs text-gray-500 mt-1">Email tidak dapat diubah.</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-400">No. HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $member->no_hp ?? '') }}"
                        class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded p-2 text-white">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-400">Alamat</label>
                    <textarea name="alamat" rows="3"
                        class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded p-2 text-white">{{ old('alamat', $member->alamat ?? '') }}</textarea>
                </div>

                <div class="flex gap-2 mt-6">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        Simpan
                    </button>
                    <a href="{{ route('anggota.profil') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                        Batal
                    </a>
                </div>

            </form>
        </div>
    </div>

</x-app-sidebar>