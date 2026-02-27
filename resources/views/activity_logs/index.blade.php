<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Activity Log
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="p-3 border">Waktu</th>
                            <th class="p-3 border">User</th>
                            <th class="p-3 border">Action</th>
                            <th class="p-3 border">Deskripsi</th>
                            <th class="p-3 border">IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border text-sm text-gray-500">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                            <td class="p-3 border">{{ $log->user->name ?? '-' }}</td>
                            <td class="p-3 border">
                                <span class="px-2 py-1 rounded text-sm
                                    @if($log->action == 'login') bg-green-100 text-green-800
                                    @elseif($log->action == 'logout') bg-gray-100 text-gray-800
                                    @elseif($log->action == 'create') bg-blue-100 text-blue-800
                                    @elseif($log->action == 'update') bg-yellow-100 text-yellow-800
                                    @elseif($log->action == 'delete') bg-red-100 text-red-800
                                    @endif">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="p-3 border">{{ $log->description }}</td>
                            <td class="p-3 border text-sm text-gray-500">{{ $log->ip_address }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $logs->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>