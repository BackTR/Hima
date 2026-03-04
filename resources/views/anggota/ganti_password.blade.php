<x-app-sidebar>
    <x-slot name="header">Ganti Password</x-slot>
    <x-slot name="breadcrumb">Home / Profil / Ganti Password</x-slot>

    <div class="max-w-md mx-auto">
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

            <form action="{{ route('anggota.updatepassword') }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-400">Password Lama</label>
                    <input type="password" name="password_lama" placeholder="Masukkan password lama"
                        class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded p-2 text-white" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-400">Password Baru</label>
                    <input type="password" name="password" placeholder="Minimal 8 karakter"
                        class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded p-2 text-white" required>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-400">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password baru"
                        class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded p-2 text-white" required>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                        Ganti Password
                    </button>
                    <a href="{{ route('anggota.profil') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                        Batal
                    </a>
                </div>

            </form>
        </div>
    </div>

</x-app-sidebar>