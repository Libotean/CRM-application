<x-layout>
    <div class="container mx-auto">
        
        <div class="flex justify-between items-end mb-8 border-b-2 border-black pb-4">
            <div>
                <h1 class="text-3xl font-extrabold text-black uppercase tracking-tight">
                    Gestionare Clienti
                </h1>
                <a href="/" class="text-gray-500 hover:text-black font-bold text-sm flex items-center mb-2 transition">
                    <span class="mr-1">&larr;</span> Inapoi
                </a>
            </div>
            
        </div>

        <div class="bg-gray-100 p-6 rounded-lg border border-gray-300 mb-8 shadow-inner">
            <form action="{{ route('admin.clients.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                
                <div class="md:col-span-5">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Cauta</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Nume, Telefon, CUI..."
                           class="w-full border-2 border-gray-300 rounded px-3 py-2 text-sm focus:border-black focus:ring-0 transition">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tip Client</label>
                    <select name="type" class="w-full border-2 border-gray-300 rounded px-3 py-2 text-sm bg-white focus:border-black focus:ring-0">
                        <option value="">- Toti -</option>
                        <option value="persoana_fizica" {{ request('type') == 'persoana_fizica' ? 'selected' : '' }}>Persoana Fizica</option>
                        <option value="persoana_juridica" {{ request('type') == 'persoana_juridica' ? 'selected' : '' }}>Persoana Juridica</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">
                        Consilier Alocat
                    </label>
                    <select name="user_id" class="w-full border-2 border-gray-300 rounded px-3 py-2 text-sm bg-white focus:border-black focus:ring-0 transition">
                        <option value="">- Toti Consilierii -</option>
                        
                        @foreach($consilieri as $consilier)
                            <option value="{{ $consilier->id }}" {{ request('user_id') == $consilier->id ? 'selected' : '' }}>
                                {{ $consilier->lastname }} {{ $consilier->firstname }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div class="md:col-span-2 flex gap-2">
                    <button type="submit" class="w-full bg-black text-white px-4 py-2 rounded text-sm font-bold hover:bg-gray-800 transition uppercase">
                        Filtreaza
                    </button>
                    @if(request()->anyFilled(['search', 'type', 'status']))
                        <a href="{{ route('admin.clients.index') }}" class="flex items-center justify-center w-12 bg-white text-red-700 border-2 border-red-200 rounded hover:bg-red-50 font-bold">✕</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-4 px-6 text-left text-xs font-bold text-gray-500 uppercase">Nume Client / Firma</th>
                        <th class="py-4 px-6 text-left text-xs font-bold text-gray-500 uppercase">Telefon</th>
                        <th class="py-4 px-6 text-left text-xs font-bold text-gray-500 uppercase">Email</th>
                        <th class="py-4 px-6 text-left text-xs font-bold text-gray-500 uppercase">Tip</th>
                        <th class="py-4 px-6 text-left text-xs font-bold text-red-700 uppercase">Consilier Alocat</th>
                        <th class="py-4 px-6 text-left text-xs font-bold text-gray-500 uppercase">Data Inreg.</th>
                        <th class="py-4 px-6 text-right text-xs font-bold text-gray-500 uppercase">Actiuni</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($clients as $client)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="py-4 px-6">
                                <div class="font-bold text-gray-900">{{ $client->fullName }}</div>
                                @if($client->type == 'persoana_juridica' && $client->cui)
                                    <div class="text-xs text-gray-500">CUI: {{ $client->cui }}</div>
                                @endif
                            </td>

                            <td class="py-4 px-6 text-sm">
                                <div class="text-gray-900">{{ $client->phone ?? '-' }}</div>
                            </td>

                            <td class="py-4 px-6 text-sm">
                                <div class="text-gray-900">{{ $client->email ?? '' }}</div>
                            </td>

                            <td class="py-4 px-6">
                                @if($client->type == 'persoana_juridica')
                                    <span class="px-2 py-1 text-xs font-bold bg-purple-100 text-purple-800 rounded uppercase">Juridica</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-bold bg-blue-100 text-blue-800 rounded uppercase">Fizica</span>
                                @endif
                            </td>

                            <td class="py-4 px-6">
                                @if($client->user)
                                    <a href="{{ route('admin.users.show', $client->user->id) }}" class="flex items-center group">
                                        <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600 mr-2 group-hover:bg-black group-hover:text-white transition">
                                            {{ substr($client->user->firstname, 0, 1) }}{{ substr($client->user->lastname, 0, 1) }}
                                        </div>
                                        <span class="text-sm font-medium text-gray-700 group-hover:text-black transition">
                                            {{ $client->user->lastname }} {{ $client->user->firstname }}
                                        </span>
                                    </a>
                                @else
                                    <span class="text-red-600 text-xs font-bold uppercase bg-red-50 px-2 py-1 rounded">Neasignat</span>
                                @endif
                            </td>

                            <td class="py-4 px-6 text-sm text-gray-500">
                                {{ $client->created_at?->format('d.m.Y') ?? '-'}}
                            </td>

                            <td class="py-4 px-6 text-right">
                                <a href="{{  route('admin.clients.show', $client->id)}}" class="text-red-600 hover:text-black uppercase font-bold text-sm">Detalii</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-500 italic">
                                Nu exista clienti inregistrati care sa corespunda filtrelor.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $clients->links() }}
        </div>

    </div>
</x-layout>