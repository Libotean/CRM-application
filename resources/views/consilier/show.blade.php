<x-layout>
    <div class="container mx-auto p-6 max-w-7xl">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800 uppercase tracking-tight">
                Client
            </h1>
            <a href="{{ route('consilier.clients.index') }}" class="text-gray-500 hover:text-black font-bold transition flex items-center">
                <span class="mr-2">&larr;</span> Inapoi
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-black">
                    <div class="mb-4">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Nume Client</span>
                        <h2 class="text-3xl font-extrabold text-black mt-1">
                            {{ $client->firstname }} {{ $client->lastname }}
                        </h2>
                        <div class="mt-2">
                            @if($client->type == 'juridica')
                                <span class="bg-purple-100 text-purple-800 text-xs font-bold px-2 py-1 rounded uppercase">Persoana Juridica</span>
                            @else
                                <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded uppercase">Persoana Fizica</span>
                            @endif
                            
                            <span class="ml-2 bg-green-100 text-green-800 text-xs font-bold px-2 py-1 rounded uppercase">{{ $client->status }}</span>
                        </div>
                    </div>

                    <div class="space-y-4 pt-4 border-t border-gray-100">
                        <div>
                            <label class="text-xs text-gray-500 font-bold uppercase">Telefon</label>
                            <p class="text-lg font-medium text-gray-900">{{ $client->phone ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 font-bold uppercase">Email</label>
                            <p class="text-gray-900 break-all">{{ $client->email }}</p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 uppercase">
                                <span class="{{ $client->cnp ? 'font-bold text-black' : 'font-medium' }}">CNP</span>
                                
                                <span class="mx-1">/</span>
                                
                                <span class="{{ $client->cui ? 'font-bold text-black' : 'font-medium' }}">CUI</span>
                            </label>

                            <p class="font-mono text-gray-700 mt-1">
                                {{ $client->cnp ?? $client->cui ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 font-bold uppercase">Adresa</label>
                            <p class="text-gray-700 text-sm">{{ $client->locality }}, {{ $client->county }}</p>
                            <p class="text-gray-500 text-xs">{{ $client->address }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-8">

                <div class="bg-white p-8 rounded-lg shadow-lg border border-gray-200">
                    <h3 class="text-xl font-bold text-black mb-6 flex items-center">
                        Adauga Interactiune
                    </h3>

                    <form action="{{ route('consilier.leads.store', $client->id) }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Persoana de contact</label>
                            <div class="flex items-center bg-gray-100 p-3 rounded border border-gray-300">
                                <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <span class="font-bold text-gray-700">{{ $client->firstname }} {{ $client->lastname }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Data *</label>
                                <input type="date" name="appointment_date" value="{{ date('Y-m-d') }}" 
                                       class="w-full border-2 border-gray-300 rounded px-3 py-2 focus:border-blue-900 focus:ring-0 font-medium text-gray-700" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Ora *</label>
                                <input type="time" name="appointment_time" value="{{ date('H:00') }}" 
                                       class="w-full border-2 border-gray-300 rounded px-3 py-2 focus:border-blue-900 focus:ring-0 font-medium text-gray-700" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Metoda *</label>
                                <select name="method" class="w-full border-2 border-gray-300 rounded px-3 py-2 focus:border-blue-900 bg-white">
                                    <option value="Telefon">Telefon</option>
                                    <option value="Email">Email</option>
                                    <option value="Showroom">Showroom</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Obiectiv *</label>
                                <select name="objective" class="w-full border-2 border-gray-300 rounded px-3 py-2 focus:border-blue-900 bg-white">
                                    <option value="Oferta">Oferta</option>
                                    <option value="Test Drive">Programare Test Drive</option>
                                    <option value="Livrare">Livrare Auto</option>
                                    <option value="General">Discutie Generala</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Mentiuni / Notite</label>
                            <textarea name="notes" rows="3" class="w-full border-2 border-gray-300 rounded px-3 py-2 focus:border-blue-900 resize-none" placeholder="Detalii despre discutie..."></textarea>
                        </div>

                        <button type="submit" class="w-full bg-red-700 text-white font-bold uppercase py-3 rounded shadow hover:bg-red-800 transition transform hover:-translate-y-0.5">
                            Salveaza Interactiunea
                        </button>
                    </form>
                </div>

                <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <h3 class="font-bold text-gray-700 uppercase text-sm">Istoric Activitati</h3>
                    </div>
                    
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Data</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Metoda</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Obiectiv</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Notite</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($client->leads as $lead)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                        {{ $lead->appointment_date->format('d.m.Y') }} <br>
                                        <span class="text-gray-400 font-normal">{{ $lead->appointment_date->format('H:i') }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $lead->method }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $lead->objective }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 italic max-w-xs">
                                        <div class="max-h-20 overflow-y-auto whitespace-normal break-words pr-2">
                                            {{ $lead->notes ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <form action="{{ route('consilier.leads.toggle', $lead->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            
                                            @if($lead->is_completed)
                                                <button type="submit" class="bg-green-100 text-green-800 text-xs font-bold px-2 py-1 rounded border border-green-200 hover:bg-green-200 transition">
                                                    COMPLET
                                                </button>
                                            @else
                                                <button type="submit" class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-1 rounded border border-yellow-200 hover:bg-yellow-200 transition">
                                                    IN ASTEPTARE
                                                </button>
                                            @endif
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500 italic">
                                        Nu exista interactiuni inregistrate pentru acest client.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-layout>