<x-layout>
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- HEADER PAGINĂ CU SEARCH BAR --}}
            <div class="mb-8 flex flex-col md:flex-row justify-between items-end md:items-center gap-4">

                {{-- 1. Titlul --}}
                <div class="w-full md:w-auto">
                    <h2 class="text-3xl font-extrabold text-gray-900">
                        Stoc Vehicule
                    </h2>
                </div>

                {{-- 2. Bara de Cautare --}}
                <form action="{{ route('vehicles.index') }}" method="GET" class="w-full md:w-1/3">
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cauta marca, model sau VIN..."
                            class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-red-600 focus:border-transparent shadow-sm transition"
                        >
                        {{-- Iconiță Lupă --}}
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>

                        {{-- Buton Reset (apare doar dacă ai căutat ceva) --}}
                        @if(request('search'))
                            <a href="{{ route('vehicles.index') }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-600" title="Șterge filtrarea">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </form>

                {{-- 3. Butonul Înapoi --}}
                <a href="{{ route('consilier.clients.index') }}" class="group bg-red-700 text-white py-2.5 px-5 rounded-md hover:bg-red-800 font-semibold transition shadow-sm flex items-center whitespace-nowrap">
                    <span class="group-hover:-translate-x-1 transition-transform duration-200">←</span>
                    <span class="ml-2">Înapoi la Clienți</span>
                </a>
            </div>

            {{-- GRID VEHICULE --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($vehicles as $car)
                    {{-- Card Vehicul --}}
                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 hover:shadow-md transition duration-300 relative border-t-4 border-t-red-700">
                        <div class="p-6 flex flex-col h-full">

                            {{-- Titlu și Categorie --}}
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="text-2xl font-black text-gray-800 leading-tight">
                                        {{ $car->make->name ?? 'N/A' }} {{ $car->model->name ?? '' }}
                                    </h3>
                                    <p class="text-sm font-medium text-gray-500 mt-1">{{ $car->version_name }}</p>
                                </div>
                                <div class="flex flex-col gap-1 items-end">
                                    {{-- Badge Categorie --}}
                                    <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-2 py-0.5 rounded border border-gray-200 uppercase">
                                        {{ $car->model->category->name ?? 'Auto' }}
                                    </span>

                                    {{-- Badge STATUS (NOU / RULAT) --}}
                                    @if(isset($car->condition) && strtolower($car->condition) == 'nou')
                                        <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded border border-blue-200 uppercase">
                                            NOU
                                        </span>
                                    @else
                                        <span class="bg-orange-100 text-orange-700 text-[10px] font-bold px-2 py-0.5 rounded border border-orange-200 uppercase">
                                            RULAT
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="my-5 grid grid-cols-2 gap-y-4 gap-x-4">


                                {{-- 1. KILOMETRAJ (Stil Bord / Vitezometru) --}}
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-800" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 3c-4.97 0-9 4.03-9 9v2c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-2c0-4.97-4.03-9-9-9zm0 11c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.54-3.54l-2.12 2.12c-.17.17-.41.24-.63.19-.23-.05-.43-.2-.53-.41-.1-.21-.06-.46.09-.63l2.12-2.12c.39-.39 1.02-.39 1.41 0 .4.39.4 1.03-.34 1.77z"/>
                                        <circle cx="7" cy="12" r="1" opacity="0.6"/>
                                        <circle cx="17" cy="12" r="1" opacity="0.6"/>
                                        <circle cx="12" cy="7" r="1" opacity="0.6"/>
                                    </svg>
                                    <div class="ml-3 leading-none">
                                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wide mb-0.5">Kilometraj</p>
                                        <p class="text-sm font-black text-gray-900">
                                            {{ $car->mileage > 0 ? number_format($car->mileage, 0, '.', ' ') . ' km' : '0 km' }}
                                        </p>
                                    </div>
                                </div>
                                {{-- 2. DATA FABRICARE (Calendar Solid) --}}
                                <div class="flex items-center">
                                    {{-- Iconiță: Calendar Plin --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-800" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                    <div class="ml-3 leading-none">
                                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wide mb-0.5">Data fabricare</p>
                                        <p class="text-sm font-black text-gray-900">{{ $car->manufacturing_year ?? '-' }}</p>
                                    </div>
                                </div>

                                {{-- 3. PUTERE (Iconiță stil Check Engine) --}}
                                <div class="flex items-center">
                                    {{-- Iconiță: Check Engine / Bloc Motor Plin --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-800" viewBox="0 0 24 24" fill="currentColor">
                                        {{-- Corpul principal --}}
                                        <path d="M7 6h10c1.1 0 2 .9 2 2v9c0 1.1-.9 2-2 2H7c-1.1 0-2-.9-2-2V8c0-1.1.9-2 2-2z"/>
                                        {{-- Capacul de sus (Valva) --}}
                                        <path d="M10 3h4v3h-4z"/>
                                        {{-- Element lateral stânga --}}
                                        <path d="M3 9h2v5H3z"/>
                                        {{-- Element lateral dreapta (Ventilator) --}}
                                        <path d="M19 9h2v5h-2z"/>
                                    </svg>
                                    <div class="ml-3 leading-none">
                                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wide mb-0.5">Putere</p>
                                        <p class="text-sm font-black text-gray-900">{{ $car->power_hp ? $car->power_hp . ' CP' : '-' }}</p>
                                    </div>
                                </div>

                                {{-- 4. COMBUSTIBIL (Pompă Solidă - Model Exact Imagine) --}}
                                <div class="flex items-center">
                                    {{-- Iconiță: Pompă Benzină Solidă cu Fereastră (Exact ca în poză) --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-800" viewBox="0 0 24 24" fill="currentColor">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M19.77 7.23l.01-.01-3.72-3.72L15 4.56l2.11 2.11c-.94.36-1.61 1.26-1.61 2.33 0 1.38 1.12 2.5 2.5 2.5.36 0 .69-.08 1-.21v7.21c0 .55-.45 1-1 1s-1-.45-1-1V14c0-1.1-.9-2-2-2h-1V5c0-1.1-.9-2-2-2H6c-1.1 0-2 .9-2 2v16h10v-7.5h1.5v5c0 1.38 1.12 2.5 2.5 2.5s2.5-1.12 2.5-2.5V9c0-.69-.28-1.32-.73-1.77zM12 10H6V5h6v5z"/>
                                    </svg>
                                    <div class="ml-3 leading-none">
                                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wide mb-0.5">Combustibil</p>
                                        <p class="text-sm font-black text-gray-900">{{ $car->fuel_type ?? '-' }}</p>
                                    </div>
                                </div>

                                {{-- 5. TRANSMISIE (Schema Schimbător - Stil Imagine) --}}
                                <div class="flex items-center">
                                    {{-- Iconiță: Schema Schimbător Viteze (H-Pattern) --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-800" viewBox="0 0 24 24" fill="currentColor">
                                        {{-- Punctele (Vitezele) --}}
                                        <circle cx="5" cy="6" r="2.5" />
                                        <circle cx="12" cy="6" r="2.5" />
                                        <circle cx="19" cy="6" r="2.5" />
                                        <circle cx="5" cy="18" r="2.5" />
                                        <circle cx="12" cy="18" r="2.5" />

                                        {{-- Liniile de legătură (Groase) --}}
                                        <path d="M5 6v12M12 6v12M19 6v6M5 12h14" stroke="currentColor" stroke-width="3" stroke-linecap="round" />
                                    </svg>
                                    <div class="ml-3 leading-none">
                                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wide mb-0.5">Transmisie</p>
                                        <p class="text-sm font-black text-gray-900">{{ $car->transmission ?? '-' }}</p>
                                    </div>
                                </div>

                                {{-- 8. CULOARE (Paletă Solidă - Stil Profesional) --}}
                                <div class="flex items-center">
                                    {{-- Iconiță: Paletă de culori (Solid Black) --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-800" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 3a9 9 0 0 0 0 18c.83 0 1.5-.67 1.5-1.5 0-.39-.15-.74-.39-1.01-.23-.26-.38-.61-.38-.99 0-.83.67-1.5 1.5-1.5H16c2.76 0 5-2.24 5-5 0-4.42-4.03-8-9-8zm-5.5 9c-.83 0-1.5-.67-1.5-1.5S5.67 9 6.5 9 8 9.67 8 10.5 7.33 12 6.5 12zm3-4C8.67 8 8 7.33 8 6.5S8.67 5 9.5 5s1.5.67 1.5 1.5S10.33 8 9.5 8zm5 0c-.83 0-1.5-.67-1.5-1.5S13.67 5 14.5 5s1.5.67 1.5 1.5S15.33 8 14.5 8zm3 4c-.83 0-1.5-.67-1.5-1.5S16.67 9 17.5 9s1.5.67 1.5 1.5-.67 1.5-1.5 1.5z"/>
                                    </svg>
                                    <div class="ml-3 leading-none">
                                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wide mb-0.5">Culoare</p>
                                        <p class="text-sm font-black text-gray-900">{{ $car->color ?? '-' }}</p>
                                    </div>
                                </div>

                            </div>
                            {{-- ========================================== --}}
                            {{-- VIN și PREȚ --}}
                            <div class="space-y-3 mb-6 flex-grow">
                                <div class="flex items-center bg-gray-50 p-2 rounded">
                                    <span class="text-xs font-semibold text-gray-500 uppercase mr-2">VIN:</span>
                                    <span class="font-mono text-sm text-gray-700 truncate">{{ $car->vin ?? '-' }}</span>
                                </div>

                                {{-- PREȚ --}}
                                <div class="flex items-end justify-between border-b pb-2 border-dashed">
                                    <span class="text-sm text-gray-500 self-center">Preț de listă:</span>
                                    <span class="text-3xl font-black text-gray-900">
                                        {{ number_format($car->price_eur, 0, '.', '.') }} €
                                    </span>
                                </div>
                            </div>

                            {{-- Footer Card --}}
                            <div class="pt-4 mt-auto border-t border-gray-50">
                                @if($car->client_id)
                                    <div class="bg-red-50 border border-red-100 p-3 rounded-lg text-center">
                                        <span class="flex items-center justify-center text-xs font-bold text-red-700 uppercase tracking-widest mb-1">
                                            Vanduta catre
                                        </span>
                                        <span class="text-base font-bold text-gray-800">
                                            {{ $car->client->lastname }} {{ $car->client->firstname }}
                                        </span>
                                    </div>
                                @else
                                    <div class="flex flex-col gap-3">
                                        <div class="flex items-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                                                <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-500 fill-current" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                                DISPONIBIL IN STOC
                                            </span>
                                        </div>

                                        {{-- Butonul ROSU (Brand) --}}
                                        <a href="{{ route('vehicles.sell', $car->id) }}" class="group w-full flex items-center justify-center bg-red-700 text-white px-4 py-3 rounded-md text-sm uppercase font-bold hover:bg-red-800 transition shadow-sm hover:shadow relative overflow-hidden">
                                            <span class="relative z-10 flex items-center">
                                                Asigneaza Client
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                                            </span>
                                        </a>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-layout>
