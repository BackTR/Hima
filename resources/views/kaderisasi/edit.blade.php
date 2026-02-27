<x-app-sidebar>
    <x-slot name="header">Edit Kaderisasi</x-slot>
    <x-slot name="breadcrumb">Home / Kaderisasi / Edit</x-slot>

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

        <form action="{{ route('kaderisasi.update', $kaderisasi->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div class="mb-4 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-400">Anggota</label>
                    <select name="member_id" class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded p-2 text-white" required>
                        <option value="">-- Pilih Anggota --</option>
                        @foreach($members as $member)
                            <option value="{{ $member->id }}" {{ $kaderisasi->member_id == $member->id ? 'selected' : '' }}>
                                {{ $member->user->name }} ({{ $member->divisi ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-400">Level</label>
                    <input type="text" name="level" value="{{ old('level', $kaderisasi->level) }}"
                        class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded p-2 text-white" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-400">Status</label>
                    <select name="status" class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded p-2 text-white" required>
                        <option value="proses" {{ $kaderisasi->status == 'proses' ? 'selected' : '' }}>Proses</option>
                        <option value="lulus" {{ $kaderisasi->status == 'lulus' ? 'selected' : '' }}>Lulus</option>
                        <option value="gagal" {{ $kaderisasi->status == 'gagal' ? 'selected' : '' }}>Gagal</option>
                    </select>
                </div>

                <div class="mb-4 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-400">Catatan</label>
                    <textarea name="catatan" rows="3" class="mt-1 block w-full bg-gray-700 border border-gray-600 rounded p-2 text-white">{{ old('catatan', $kaderisasi->catatan) }}</textarea>
                </div>

            </div>

            <div class="flex gap-2 mt-4">
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                    Update
                </button>
                <a href="{{ route('kaderisasi.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                    Batal
                </a>
            </div>

        </form>
    </div>

</x-app-sidebar>