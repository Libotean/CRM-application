<x-layout>
    <div class="w-full px-6 py-6 bg-gray-50 min-h-screen">

        {{-- HEADER --}}
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

            {{-- SIDEBAR: PROFIL --}}
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
                        <div class="group"><label class="text-xs text-gray-400 font-bold uppercase mb-1 block">Telefon</label><div class="flex items-center text-gray-900 font-medium text-lg">{{ $client->phone ?? '-' }}</div></div>
                        <div class="group"><label class="text-xs text-gray-400 font-bold uppercase mb-1 block">Email</label><div class="flex items-center text-gray-900 font-medium break-all">{{ $client->email }}</div></div>
                        <div class="group"><label class="text-xs text-gray-400 font-bold uppercase mb-1 block">{{ $client->cui ? 'CUI' : 'CNP' }}</label><p class="font-mono text-gray-600 bg-gray-100 px-3 py-2 rounded border border-gray-200 inline-block w-full">{{ $client->cnp ?? $client->cui ?? '-' }}</p></div>
                        <div class="group"><label class="text-xs text-gray-400 font-bold uppercase mb-1 block">Adresa</label><p class="text-gray-800 leading-snug">{{ $client->locality }}, {{ $client->county }}</p><p class="text-gray-500 text-xs mt-1">{{ $client->address }}</p></div>

                        {{-- VEHICULE ASIGNATE --}}
                        @if($client->vehicles->isNotEmpty())
                            <div class="group border-t border-dashed border-gray-200 pt-4 mt-2">
                                <label class="text-xs text-gray-400 font-bold uppercase mb-2 block">Vehicule Asignate</label>
                                <div class="flex flex-col gap-2">
                                    @foreach($client->vehicles as $vehicle)
                                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                                            <span class="text-sm font-bold text-gray-900">{{ $vehicle->make->name ?? '' }} {{ $vehicle->model->name ?? '' }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- CONTINUT PRINCIPAL --}}
            <div class="lg:col-span-9 space-y-8">

                {{-- 1. FORMULAR TEST DRIVE (NOU) --}}
                <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                        <div class="bg-black text-white w-10 h-10 flex items-center justify-center rounded-full shadow-md"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg></div>
                        <h3 class="text-xl font-bold text-gray-800">Programeaza Test Drive</h3>
                    </div>
                    <form action="{{ route('test_drives.store', $client->id) }}" method="POST">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Vehicul Asignat</label>
                                @if($client->vehicles->isNotEmpty())
                                    @php $assignedCar = $client->vehicles->first(); @endphp
                                    <input type="hidden" name="vehicle_id" value="{{ $assignedCar->id }}">
                                    <div class="w-full border border-gray-200 bg-gray-100 rounded-lg px-4 py-3 text-gray-700 font-bold flex items-center justify-between shadow-sm">
                                        <div class="flex items-center gap-3">
                                            <span>{{ $assignedCar->make->name ?? 'Marca' }} {{ $assignedCar->model->name ?? 'Model' }} <span class="text-gray-500 font-normal text-sm ml-1">- {{ $assignedCar->vin ?? 'Fara VIN' }}</span></span>
                                        </div>
                                        <div class="text-xs font-bold text-gray-400 uppercase border border-gray-300 px-2 py-1 rounded">ASIGNAT</div>
                                    </div>
                                @else
                                    <div class="p-4 bg-red-50 text-red-700 rounded-lg border border-red-200 flex items-center">Eroare: Acest client nu are niciun vehicul asignat!</div>
                                @endif
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div><label class="block text-xs font-bold text-gray-400 uppercase mb-2">Data Programarii</label><input type="date" name="scheduled_date" value="{{ date('Y-m-d') }}" class="w-full border border-gray-200 rounded-lg px-4 py-3 text-gray-700"></div>
                                <div><label class="block text-xs font-bold text-gray-400 uppercase mb-2">Ora</label><input type="time" name="scheduled_time" value="{{ date('H:00') }}" class="w-full border border-gray-200 rounded-lg px-4 py-3 text-gray-700"></div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Kilometri la preluare (Start KM)</label>
                                <input type="number" name="start_km" placeholder="Ex: 15000" class="w-full border border-gray-200 rounded-lg px-4 py-3 text-gray-700">
                            </div>
                            <div class="pt-4 flex justify-end items-center gap-4">
                                <a href="{{ route('consilier.clients.show', $client->id ) }}" class="text-gray-500 font-bold text-sm hover:text-black transition cursor-pointer">Renunta</a>
                                <button type="submit" class="bg-red-700 text-white font-bold text-sm px-8 py-3 rounded-lg shadow-md hover:bg-red-800 transition">SALVEAZA TEST DRIVE</button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- 2. FORMULAR INTERACTIUNE (VECHI) --}}
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200 shadow-inner">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center"><span class="bg-black text-white w-8 h-8 flex items-center justify-center rounded-full mr-3 text-sm shadow">@</span> Adauga Interactiune</h3>
                    <form action="{{ route('consilier.leads.store', $client->id) }}" method="POST" class="bg-white p-6 rounded-lg shadow border border-gray-200">
                        @csrf
                        <div class="flex flex-col md:flex-row gap-4 mb-4">
                            <div class="flex-1"><label class="block text-xs font-bold text-gray-400 uppercase mb-1">Data/Ora</label><div class="flex gap-2"><input type="date" name="appointment_date" value="{{ date('Y-m-d') }}" class="w-full border border-gray-300 rounded px-3 py-2 text-sm"><input type="time" name="appointment_time" value="{{ date('H:00') }}" class="w-32 border border-gray-300 rounded px-3 py-2 text-sm"></div></div>
                            <div class="flex-1"><label class="block text-xs font-bold text-gray-400 uppercase mb-1">Metoda</label><select name="method" class="w-full border border-gray-300 rounded px-3 py-2 text-sm bg-white"><option value="Telefon">Telefon</option><option value="Email">Email</option><option value="Showroom">Showroom</option></select></div>
                            <div class="flex-1"><label class="block text-xs font-bold text-gray-400 uppercase mb-1">Obiectiv</label><select name="objective" class="w-full border border-gray-300 rounded px-3 py-2 text-sm bg-white"><option value="Oferta">Oferta</option><option value="Livrare">Livrare</option><option value="General">Discutie Generala</option></select></div>
                        </div>
                        <div class="flex gap-4 items-start">
                            <textarea name="notes" rows="1" class="w-full border border-gray-300 rounded px-3 py-3 text-sm resize-none" placeholder="Scrie detalii..."></textarea>
                            <button type="submit" class="bg-gray-800 text-white px-8 py-3 rounded text-sm font-bold uppercase hover:bg-black transition">Adauga</button>
                        </div>
                    </form>
                    <div class="flex justify-end mt-4"><button onclick="toggleEmailForm()" type="button" class="text-blue-700 font-bold hover:text-blue-900 flex items-center text-sm transition">Trimite un email</button></div>
                </div>

                {{-- 3. FORMULAR EMAIL (ASCUNS) --}}
                <div id="emailFormSection" class="hidden bg-blue-50 p-6 rounded-xl border border-blue-200 shadow-inner">
                    <div class="flex justify-between items-center mb-4"><h3 class="text-lg font-bold text-gray-800">Trimite Email</h3><button onclick="toggleEmailForm()" class="text-gray-400 hover:text-red-500">✕</button></div>
                    <form action="{{ route('consilier.leads.sendEmail', $client->id) }}" method="POST" class="bg-white p-6 rounded-lg shadow border border-blue-100">
                        @csrf
                        <div class="mb-4"><label class="block text-xs font-bold text-gray-400 uppercase mb-1">Subiect</label><input type="text" name="subject" class="w-full border border-gray-300 rounded px-3 py-2 text-sm" required placeholder="Subiect..."></div>
                        <div class="mb-4"><label class="block text-xs font-bold text-gray-400 uppercase mb-1">Mesaj</label><textarea name="message" rows="4" class="w-full border border-gray-300 rounded px-3 py-2 text-sm resize-none" required placeholder="Mesaj..."></textarea></div>
                        <button type="submit" class="w-full bg-blue-600 text-white font-bold uppercase py-3 rounded hover:bg-blue-700 transition">Trimite Email Acum</button>
                    </form>
                </div>

                {{-- 4. CRONOLOGIE --}}
                <div>
                    <h3 class="text-xl font-bold text-gray-800 mb-6 border-b-2 border-gray-200 pb-2">Cronologie Activitati</h3>
                    <div class="space-y-4">
                        @forelse($client->leads as $lead)
                            <div class="flex gap-6 group">
                                <div class="w-32 text-right pt-4 flex-shrink-0">
                                    <p class="font-black text-gray-900 text-lg leading-none">{{ $lead->appointment_date->format('d M') }}</p>
                                    <p class="text-sm text-gray-600 mt-1">{{ $lead->appointment_date->format('H:i') }}</p>
                                </div>
                                <div class="relative flex flex-col items-center">
                                    <div class="w-4 h-4 rounded-full {{ $lead->is_completed ? 'bg-green-500' : 'bg-white border-4 border-yellow-400' }} shadow z-10"></div>
                                    <div class="w-0.5 bg-gray-200 flex-grow -my-2 group-last:hidden"></div>
                                </div>
                                <div class="flex-grow pb-8">
                                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                                        <div class="flex justify-between items-start mb-3">
                                            <h4 class="font-bold text-xl text-gray-900">{{ $lead->objective }} <span class="text-sm font-normal text-gray-500">via {{ $lead->method }}</span></h4>
                                            <form action="{{ route('consilier.leads.toggle', $lead->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="text-xs font-bold px-4 py-2 rounded border {{ $lead->is_completed ? 'bg-green-50 text-green-700' : 'bg-white text-yellow-700 border-yellow-300' }}">@if($lead->is_completed) FINALIZAT @else IN ASTEPTARE @endif</button>
                                            </form>
                                        </div>
                                        <div class="text-gray-700 text-sm">{{ $lead->notes ?? 'Fara notite.' }}</div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 py-10">Nu exista istoric.</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function toggleEmailForm() {
            const form = document.getElementById('emailFormSection');
            form.classList.toggle('hidden');
        }
    </script>
</x-layout>
