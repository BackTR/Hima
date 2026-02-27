<x-app-sidebar>
    <x-slot name="header">Tambah Anggota</x-slot>
    <x-slot name="breadcrumb">Home / Anggota / Tambah</x-slot>

    <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">

        @if($errors->any())
            <div class="bg-red-900 text-red-300 p-3 rounded mb-4 border border-red-700">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('members.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-400">Nama</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded p-2 text-white" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-400">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded p-2 text-white" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-400">Password</label>
                    <input type="password" name="password"
                        class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded p-2 text-white" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-400">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation"
                        class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded p-2 text-white" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-400">Role</label>
                    <select name="role" class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded p-2 text-white">
                        <option value="anggota">Anggota</option>
                        <option value="pengurus">Pengurus</option>
                        <option value="admin">Admin</option>
                        <option value="superadmin">Super Admin</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-400">NIM</label>
                    <input type="text" name="nim" value="{{ old('nim') }}"
                        class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded p-2 text-white">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-400">Angkatan</label>
                    <input type="text" name="angkatan" value="{{ old('angkatan') }}"
                        class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded p-2 text-white">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-400">Divisi</label>
                    <input type="text" name="divisi" value="{{ old('divisi') }}"
                        class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded p-2 text-white">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-400">No. HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                        class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded p-2 text-white">
                </div>

                <div class="mb-4 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-400">Alamat</label>
                    <textarea name="alamat" class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded p-2 text-white">{{ old('alamat') }}</textarea>
                </div>

            </div>

            <div class="flex gap-2 mt-4">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                    Simpan
                </button>
                <a href="{{ route('members.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                    Batal
                </a>
            </div>

        </form>
    </div>

</x-app-sidebar>