<x-app-sidebar>
    <x-slot name="header">Dashboard</x-slot>
    <x-slot name="breadcrumb">Home / Dashboard</x-slot>

    @php $role = Auth::user()->role; @endphp

    {{-- Statistik untuk superadmin & admin --}}
    @if(in_array($role, ['superadmin', 'admin']))
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <p class="text-sm text-gray-400">Total User</p>
                <p class="text-3xl font-bold text-indigo-400 mt-1">{{ $stats['total_user'] }}</p>
            </div>
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <p class="text-sm text-gray-400">Total Anggota</p>
                <p class="text-3xl font-bold text-blue-400 mt-1">{{ $stats['total_anggota'] }}</p>
            </div>
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <p class="text-sm text-gray-400">Anggota Aktif</p>
                <p class="text-3xl font-bold text-emerald-400 mt-1">{{ $stats['anggota_aktif'] }}</p>
            </div>
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <p class="text-sm text-gray-400">Anggota Nonaktif</p>
                <p class="text-3xl font-bold text-red-400 mt-1">{{ $stats['anggota_nonaktif'] }}</p>
            </div>
        </div>

        {{-- Anggota Terbaru --}}
        <div class="bg-gray-800 rounded-lg border border-gray-700 p-6 mb-6">
            <div class="flex justify-between mb-4">
                <h3 class="text-lg font-semibold text-white">Anggota Terbaru</h3>
                <a href="{{ route('members.index') }}" class="text-indigo-400 text-sm hover:underline">Lihat Semua</a>
            </div>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-700">
                        <th class="p-3 border border-gray-600 text-gray-300">Nama</th>
                        <th class="p-3 border border-gray-600 text-gray-300">Email</th>
                        <th class="p-3 border border-gray-600 text-gray-300">Divisi</th>
                        <th class="p-3 border border-gray-600 text-gray-300">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentMembers as $user)
                    <tr class="hover:bg-gray-700 border-b border-gray-700">
                        <td class="p-3 border border-gray-700 text-gray-200">{{ $user->name }}</td>
                        <td class="p-3 border border-gray-700 text-gray-200">{{ $user->email }}</td>
                        <td class="p-3 border border-gray-700 text-gray-200">{{ $user->member->divisi ?? '-' }}</td>
                        <td class="p-3 border border-gray-700">
                            <span class="px-2 py-1 rounded text-sm {{ $user->is_active ? 'bg-green-900 text-green-300' : 'bg-red-900 text-red-300' }}">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Log Aktivitas Terbaru untuk superadmin --}}
    @if($role === 'superadmin')
        <div class="bg-gray-800 rounded-lg border border-gray-700 p-6 mb-6">
            <div class="flex justify-between mb-4">
                <h3 class="text-lg font-semibold text-white">Log Aktivitas Terbaru</h3>
                <a href="{{ route('activity-log.index') }}" class="text-indigo-400 text-sm hover:underline">Lihat Semua</a>
            </div>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-700">
                        <th class="p-3 border border-gray-600 text-gray-300">Waktu</th>
                        <th class="p-3 border border-gray-600 text-gray-300">User</th>
                        <th class="p-3 border border-gray-600 text-gray-300">Action</th>
                        <th class="p-3 border border-gray-600 text-gray-300">Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentLogs as $log)
                    <tr class="hover:bg-gray-700 border-b border-gray-700">
                        <td class="p-3 border border-gray-700 text-sm text-gray-400">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        <td class="p-3 border border-gray-700 text-gray-200">{{ $log->user->name ?? '-' }}</td>
                        <td class="p-3 border border-gray-700">
                            <span class="px-2 py-1 rounded text-sm
                                @if($log->action == 'login') bg-green-900 text-green-300
                                @elseif($log->action == 'logout') bg-gray-700 text-gray-300
                                @elseif($log->action == 'create') bg-blue-900 text-blue-300
                                @elseif($log->action == 'update') bg-yellow-900 text-yellow-300
                                @elseif($log->action == 'delete') bg-red-900 text-red-300
                                @endif">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="p-3 border border-gray-700 text-gray-200">{{ $log->description }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Info user untuk semua role --}}
    <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">
        <p class="text-gray-300">Selamat datang, <span class="font-semibold text-white">{{ Auth::user()->name }}</span>!</p>
        <p class="text-sm text-gray-500 mt-1">Role: <span class="capitalize text-indigo-400">{{ Auth::user()->role }}</span></p>
    </div>

</x-app-sidebar>