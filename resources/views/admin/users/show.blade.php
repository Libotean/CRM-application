<x-layout>
    <div class="container mx-auto p-6">

        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            
            <h1 class="text-3xl font-bold text-white">
                <span class="text-blue-200 text-lg block mb-1 font-normal">Detalii User</span>
                {{ $user->full_name }}
            </h1>

            <div class="flex gap-3">
                <a href="{{ route('admin.users.index') }}" class="flex items-center bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 font-bold transition shadow">
                    <span>← Înapoi</span>
                </a>
                
                <a href="{{ route('admin.users.edit', $user->id) }}" class="flex items-center bg-yellow-500 text-white py-2 px-4 rounded-lg hover:bg-yellow-600 font-bold transition shadow">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00 2 2h11a2 2 0 00 2-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Editează
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-xl overflow-hidden">
            
            <div class="p-8 border-b border-gray-200">
                <div class="flex items-center mb-6">
                    <div class="p-3 bg-blue-100 rounded-full text-blue-700 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800">Date de Identificare</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Nume Complet</label>
                            <p class="text-lg font-medium text-gray-900">{{ $user->lastname }} {{ $user->firstname }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Email</label>
                            <p class="text-gray-900">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Rol in sistem</label>
                            <div class="mt-1">
                                <span class="px-3 py-1 text-sm font-bold rounded-full {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Locatie</label>
                            <p class="text-gray-900">{{ $user->locality }}, {{ $user->county }}</p>
                            <p class="text-sm text-gray-500">{{ $user->country }}</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                        <label class="text-xs font-bold text-gray-500 uppercase mb-2 block">Status Contract</label>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm text-gray-600">Inceput:</span>
                            <span class="font-medium">{{ $user->date_start ? \Carbon\Carbon::parse($user->date_start)->format('d.m.Y') : '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600">Sfarsit:</span>
                            <span class="font-medium {{ $user->date_end && \Carbon\Carbon::parse($user->date_end)->isPast() ? 'text-red-600' : '' }}">
                                {{ $user->date_end ? \Carbon\Carbon::parse($user->date_end)->format('d.m.Y') : 'Nedeterminat' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 p-8">
                <div class="flex items-center mb-6">
                    <div class="p-3 bg-green-100 rounded-full text-green-700 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 00 2 2h2a2 2 0 00 2-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 00 2 2h2a2 2 0 00 2-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-800">Performanta & Portofoliu</h2>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 text-center">
                        <span class="block text-3xl font-bold text-blue-700">{{ $user->clients->count() }}</span>
                        <span class="text-xs text-gray-500 font-bold uppercase">Total Clienti</span>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 text-center">
                        <span class="block text-3xl font-bold text-green-600">{{ $user->clients->where('status', 'activ')->count() }}</span>
                        <span class="text-xs text-gray-500 font-bold uppercase">Activi</span>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm">
                    <div class="px-6 py-3 bg-gray-100 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="font-bold text-gray-700 text-sm uppercase">Ultimii 5 Clienti Adaugati</h3>
                    </div>
                    
                    @if($user->clients->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nume Client</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tip</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Data Adaugarii</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($user->clients->sortByDesc('created_at')->take(5) as $client)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $client->full_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="px-2 py-1 rounded text-xs font-bold {{ $client->type == 'juridica' ? 'bg-orange-100 text-orange-800' : 'bg-teal-100 text-teal-800' }}">
                                                {{ ucfirst($client->type) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $client->created_at->format('d.m.Y') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="p-8 text-center">
                            <p class="text-gray-500 italic">Acest consilier nu are inca niciun client asignat.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-layout>