<x-app-sidebar>
    <x-slot name="header">Tambah Kaderisasi</x-slot>
    <x-slot name="breadcrumb">Home / Kaderisasi / Tambah</x-slot>

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

        <form action="{{ route('kaderisasi.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div class="mb-4 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-400">Anggota</label>
                    <select name="member_id" class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded p-2 text-white" required>
                        <option value="">-- Pilih Anggota --</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                {{ $member->user->name }} ({{ $member->divisi ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-400">Level</label>
                    <input type="text" name="level" value="{{ old('level') }}"
                        placeholder="contoh: Basic, Intermediate, Advanced"
                        class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded p-2 text-white" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-400">Status</label>
                    <select name="status" class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded p-2 text-white" required>
                        <option value="proses" {{ old('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                        <option value="lulus" {{ old('status') == 'lulus' ? 'selected' : '' }}>Lulus</option>
                        <option value="gagal" {{ old('status') == 'gagal' ? 'selected' : '' }}>Gagal</option>
                    </select>
                </div>

                <div class="mb-4 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-400">Catatan</label>
                    <textarea name="catatan" rows="3" class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded p-2 text-white">{{ old('catatan') }}</textarea>
                </div>

            </div>

            <div class="flex gap-2 mt-4">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                    Simpan
                </button>
                <a href="{{ route('kaderisasi.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                    Batal
                </a>
            </div>

        </form>
    </div>

</x-app-sidebar>