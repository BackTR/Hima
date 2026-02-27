<x-app-sidebar>
    <x-slot name="header">Activity Log</x-slot>
    <x-slot name="breadcrumb">Home / Activity Log</x-slot>

    <div class="bg-gray-800 rounded-lg border border-gray-700 p-6">

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-700">
                    <th class="p-3 border border-gray-600 text-gray-300">Waktu</th>
                    <th class="p-3 border border-gray-600 text-gray-300">User</th>
                    <th class="p-3 border border-gray-600 text-gray-300">Action</th>
                    <th class="p-3 border border-gray-600 text-gray-300">Deskripsi</th>
                    <th class="p-3 border border-gray-600 text-gray-300">IP Address</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                <tr class="hover:bg-gray-700 border-b border-gray-700">
                    <td class="p-3 border border-gray-700 text-sm text-gray-400">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
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
                    <td class="p-3 border border-gray-700 text-sm text-gray-400">{{ $log->ip_address }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $logs->links() }}
        </div>

    </div>

</x-app-sidebar>