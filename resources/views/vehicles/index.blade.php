<x-layout>
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- HEADER PAGINĂ --}}
            <div class="mb-8 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900">
                        Stoc Vehicule
                    </h2>
                </div>

                <a href="{{ route('consilier.clients.index') }}" class="group bg-red-700 text-white py-2.5 px-5 rounded-md hover:bg-red-800 font-semibold transition shadow-sm flex items-center">
                    <span class="group-hover:-translate-x-1 transition-transform duration-200">←</span>
                    <span class="ml-2">Inapoi la Clienti</span>
                </a>
            </div>

            {{-- GRID VEHICULE --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($vehicles as $car)
                    {{-- Card Vehicul --}}
                    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 hover:shadow-md transition duration-300 relative border-t-4 border-t-red-700">
                        <div class="p-6 flex flex-col h-full">

                            {{-- Titlu și Categorie --}}
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-2xl font-black text-gray-800 leading-tight">
                                        {{ $car->make->name ?? 'N/A' }} {{ $car->model->name ?? '' }}
                                    </h3>
                                    <p class="text-sm font-medium text-gray-500 mt-1">{{ $car->version_name }}</p>
                                </div>
                                <span class="bg-gray-100 text-gray-600 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                                    {{ $car->model->category->name ?? 'Auto' }}
                                </span>
                            </div>

                            {{-- Detalii --}}
                            <div class="space-y-3 mb-6 flex-grow">
                                <div class="flex items-center bg-gray-50 p-2 rounded">
                                    <span class="text-xs font-semibold text-gray-500 uppercase mr-2">VIN:</span>
                                    <span class="font-mono text-sm text-gray-700 truncate">{{ $car->vin ?? '-' }}</span>
                                </div>

                                {{-- PREȚ NEGRU (Gray-900) --}}
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
