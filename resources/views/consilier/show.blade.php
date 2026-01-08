<x-layout>
    <div class="w-full px-6 py-6">

        <div class="flex justify-between items-center mb-8 pb-4 border-b border-gray-300">
            <h1 class="text-3xl font-extrabold text-gray-900 uppercase tracking-tight flex items-center">
                <span class="bg-red-700 text-white w-10 h-10 flex items-center justify-center rounded-lg mr-3 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </span>
                Informatii Client
            </h1>
            <a href="{{ route('consilier.clients.index') }}" class="text-gray-600 hover:text-black font-bold transition flex items-center bg-white border border-gray-300 px-5 py-2 rounded-lg shadow-sm hover:shadow-md">
                <span class="mr-2">&larr;</span> Inapoi
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <div class="lg:col-span-3 space-y-6 sticky top-6">
                <div class="bg-white p-6 rounded-xl shadow-lg border-t-8 border-black">
                    <div class="text-center mb-8">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center text-2xl font-bold text-gray-500 mx-auto mb-4 border-4 border-white shadow">
                            {{ substr($client->firstname, 0, 1) }}{{ substr($client->lastname, 0, 1) }}
                        </div>
                        <h2 class="text-2xl font-extrabold text-black uppercase leading-tight">
                            {{ $client->firstname }} <br> {{ $client->lastname }}
                        </h2>

                        <div class="mt-4 flex flex-col gap-2">
                            <span class="px-3 py-1 text-xs font-bold rounded uppercase border {{ $client->type == 'juridica' ? 'bg-purple-50 text-purple-700 border-purple-200' : 'bg-blue-50 text-blue-700 border-blue-200' }}">
                                {{ $client->type == 'juridica' ? 'Persoana Juridica' : 'Persoana Fizica' }}
                            </span>
                            <span class="px-3 py-1 text-xs font-bold rounded uppercase border {{ $client->status == 'activ' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200' }}">
                                Cont {{ $client->status }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-5 pt-6 border-t border-gray-100 text-sm">
                        <div class="group">
                            <label class="text-xs text-gray-400 font-bold uppercase mb-1 block">Telefon</label>
                            <div class="flex items-center text-gray-900 font-medium text-lg">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                {{ $client->phone ?? '-' }}
                            </div>
                        </div>

                        <div class="group">
                            <label class="text-xs text-gray-400 font-bold uppercase mb-1 block">Email</label>
                            <div class="flex items-center text-gray-900 font-medium break-all">
                                <svg class="w-4 h-4 text-gray-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                {{ $client->email }}
                            </div>
                        </div>

                        <div class="group">
                            <label class="text-xs text-gray-400 font-bold uppercase mb-1 block">{{ $client->cui ? 'CUI' : 'CNP' }}</label>
                            <p class="font-mono text-gray-600 bg-gray-100 px-3 py-2 rounded border border-gray-200 inline-block w-full">
                                {{ $client->cnp ?? $client->cui ?? '-' }}
                            </p>
                        </div>

                        <div class="group">
                            <label class="text-xs text-gray-400 font-bold uppercase mb-1 block">Adresa</label>
                            <p class="text-gray-800 leading-snug">{{ $client->locality }}, {{ $client->county }}</p>
                            <p class="text-gray-500 text-xs mt-1">{{ $client->address }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-9 space-y-8">

                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 shadow-inner">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <span class="bg-black text-white w-8 h-8 flex items-center justify-center rounded-full mr-3 text-sm shadow">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </span>
                        Adauga Interactiune Noua
                    </h3>

                    <form action="{{ route('consilier.leads.store', $client->id) }}" method="POST" class="bg-white p-6 rounded-lg shadow border border-gray-200">
                        @csrf
                        <div class="flex flex-col md:flex-row gap-4 mb-4">

                            <div class="flex-1">
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Data si Ora</label>
                                <div class="flex gap-2">
                                    <input type="date" name="appointment_date" value="{{ date('Y-m-d') }}"
                                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-red-700 focus:ring-1 focus:ring-red-700 transition">

                                    <input type="time" name="appointment_time" value="{{ date('H:00') }}"
                                        class="w-32 border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:border-red-700 focus:ring-1 focus:ring-red-700 transition">
                                </div>
                            </div>

                            <div class="flex-1">
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Metoda</label>
                                <select name="method"
                                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm bg-white focus:outline-none focus:border-red-700 focus:ring-1 focus:ring-red-700 transition">
                                    <option value="Telefon">Telefon</option>
                                    <option value="Email">Email</option>
                                    <option value="Showroom">Showroom</option>
                                </select>
                            </div>

                            <div class="flex-1">
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Obiectiv</label>
                                <select name="objective"
                                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm bg-white focus:outline-none focus:border-red-700 focus:ring-1 focus:ring-red-700 transition">
                                    <option value="Oferta">Oferta</option>
                                    <option value="Test Drive">Test Drive</option>
                                    <option value="Livrare">Livrare</option>
                                    <option value="General">Discutie Generala</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex gap-4 items-start">
                            <div class="flex-grow">
                                <textarea name="notes" rows="1"
                                        class="w-full border border-gray-300 rounded px-3 py-3 text-sm focus:outline-none focus:border-red-700 focus:ring-1 focus:ring-red-700 transition resize-none"
                                        placeholder="Scrie detalii..."></textarea>
                            </div>
                            <button type="submit" class="bg-red-700 text-white px-8 py-3 rounded text-sm font-bold uppercase hover:bg-red-800 transition shadow-lg flex-shrink-0 border border-red-700">
                                Salveaza
                            </button>
                        </div>
                    </form>
                </div>

                <div>
                    <h3 class="text-xl font-bold text-gray-800 mb-6 border-b-2 border-gray-200 pb-2">Cronologie Activitati</h3>

                    <div class="space-y-4">
                        @forelse($client->leads as $lead)
                            <div class="flex gap-6 group">
                                <div class="w-32 text-right pt-4 flex-shrink-0">
                                    <p class="font-black text-gray-900 text-lg leading-none">{{ $lead->appointment_date->format('d M') }}</p>
                                    <p class="text-xs text-gray-400 font-bold uppercase mt-1">{{ $lead->appointment_date->format('l') }}</p>
                                    <p class="text-sm text-gray-600 mt-1 bg-gray-100 inline-block px-2 py-0.5 rounded">{{ $lead->appointment_date->format('H:i') }}</p>
                                </div>

                                <div class="relative flex flex-col items-center">
                                    <div class="w-4 h-4 rounded-full {{ $lead->is_completed ? 'bg-green-500' : 'bg-white border-4 border-yellow-400' }} shadow z-10"></div>
                                    <div class="w-0.5 bg-gray-200 flex-grow -my-2 group-last:hidden"></div>
                                </div>

                                <div class="flex-grow pb-8">
                                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition hover:border-red-200 relative">

                                        <div class="flex justify-between items-start mb-3">
                                            <div>
                                                <h4 class="font-bold text-xl text-gray-900">{{ $lead->objective }}</h4>
                                                <div class="flex items-center gap-3 mt-1">
                                                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wide flex items-center">
                                                        <span class="w-2 h-2 bg-gray-400 rounded-full mr-1"></span> {{ $lead->method }}
                                                    </span>
                                                </div>
                                            </div>

                                            <form action="{{ route('consilier.leads.toggle', $lead->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="text-xs font-bold px-4 py-2 rounded border transition flex items-center gap-2 {{ $lead->is_completed ? 'bg-green-50 text-green-700 border-green-200 hover:bg-green-100' : 'bg-white text-yellow-700 border-yellow-300 hover:bg-yellow-50 shadow-sm' }}">
                                                    @if($lead->is_completed)
                                                        FINALIZAT
                                                    @else
                                                        IN ASTEPTARE
                                                    @endif
                                                </button>
                                            </form>
                                        </div>

                                        @if($lead->notes)
                                            <div class="text-gray-700 text-sm leading-relaxed border-t border-gray-100 pt-3 mt-2">
                                                {{ $lead->notes }}
                                            </div>
                                        @else
                                            <p class="text-gray-400 text-xs italic mt-2">Fara notite suplimentare.</p>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center py-16 bg-white rounded-xl border-2 border-dashed border-gray-300">
                                <div class="bg-gray-100 p-4 rounded-full mb-3">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <p class="text-gray-500 font-medium">Nu exista istoric pentru acest client.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-layout>
