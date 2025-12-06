<x-layout>
    <div class="container mx-auto">
        
        <div class="flex justify-between items-center mb-8 border-b-2 border-black pb-4">
            <div>
                <h1 class="text-3xl font-extrabold text-black uppercase tracking-tight">
                    Gestionare Clienti
                </h1>
                <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-black font-bold text-sm flex items-center mb-2 transition">
                    <span class="mr-1">&larr;</span> Inapoi
                </a>
            
            </div>
            <a href="{{ route('consilier.clients.create') }}" class="bg-red-700 text-white px-6 py-3 rounded hover:bg-red-800 transition font-bold shadow-sm flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                ADAUGA NOU
            </a>
        </div>

        @if (session('success'))
            <div id="flash-message" class="bg-green-100 border-l-4 border-green-600 text-green-800 p-4 rounded shadow-sm mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif
        <div class="bg-gray-100 p-6 rounded-lg border border-gray-300 mb-8 shadow-inner">
            <form action="{{ route('consilier.clients.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                
                <div class="md:col-span-4">
                    <label for="search" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">
                        Cauta (Nume / Email)
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" id="search" 
                               value="{{ request('search') }}" 
                               placeholder="Popescu, admin@atp..."
                               class="w-full pl-10 border-2 border-gray-300 rounded px-3 py-2 text-sm focus:border-black focus:ring-0 transition">
                    </div>
                </div>

                <div class="md:col-span-3">
                    <label for="status" class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">
                        Status Cont
                    </label>
                    <select name="status" id="status" class="w-full border-2 border-gray-300 rounded px-3 py-2 text-sm bg-white focus:border-black focus:ring-0 transition">
                        <option value="">- Orice Status -</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activ</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactiv</option>
                    </select>
                </div>

                <div class="md:col-span-2 flex gap-2">
                    <button type="submit" class="w-full bg-black text-white border-2 border-black px-4 py-2 rounded text-sm font-bold hover:bg-gray-800 transition uppercase tracking-wide">
                        Cauta
                    </button>
                    
                    @if(request()->anyFilled(['search', 'role', 'status']))
                        <a href="{{ route('consilier.clients.index') }}" 
                           class="flex items-center justify-center w-12 bg-white text-red-700 border-2 border-red-200 hover:border-red-700 hover:bg-red-50 rounded transition"
                           title="Sterge Filtrele">
                            ✕
                        </a>
                    @endif
                </div>
            </form>
        </div>
        <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-4 px-6 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nume Complet</th>
                        <th class="py-4 px-6 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="py-4 px-6 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status Cont</th>
                        <th class="py-4 px-6 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actiuni</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($clients as $client)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="py-4 px-6 whitespace-nowrap">
                            <div class="font-bold text-gray-900">{{ $client->full_name }}</div>
                        </td>
                        
                        <td class="py-4 px-6 whitespace-nowrap text-gray-600">
                            {{ $client->email }}
                        </td>

                        <td class="py-4 px-6 whitespace-nowrap">
                            @if ($client->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5"></span> Activ
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <span class="w-2 h-2 bg-red-500 rounded-full mr-1.5"></span> Inactiv
                                </span>
                            @endif
                        </td>

                        <td class="py-4 px-6 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-3">
                                <a href="#" 
                                class="text-sm font-bold text-red-700 hover:text-black transition uppercase tracking-wide mr-2">
                                    Detalii
                                </a>

                                <a href="#" 
                                class="text-gray-400 hover:text-blue-600 transition p-1" 
                                title="Editeaza">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            
            @if($clients->isEmpty())
                <div class="p-8 text-center text-gray-500">
                    Nu exista niciun utilizator inregistrat.
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const flashMessage = document.getElementById('flash-message');
            if(flashMessage) {
                setTimeout(()=> {
                    flashMessage.style.transition = 'opacity 0.5s ease-out';
                    flashMessage.style.opacity = '0';
                    setTimeout(() => { flashMessage.style.display = 'none'; }, 500);
                }, 4000);
            }
        });
    </script>
</x-layout>