<x-layout>
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Rapoarte si Statistici</h1>
        <p class="text-gray-600 mt-1">Analiza detaliata a vanzarilor si performantei</p>
    </div>

    {{-- FILTRE --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-gray-800">Filtre Rapoarte</h2>
            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-red-700 font-bold text-sm flex items-center transition group">
                &larr; Inapoi
            </a>
        </div>
        
        <form method="GET" action="{{ route('admin.rapoarte.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Data Start</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Data Sfarsit</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500">
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Consilier</label>
                <select name="consilier_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500">
                    <option value="">Toti consilierii</option>
                    @foreach($consilieri as $consilier)
                        <option value="{{ $consilier->id }}" {{ $consilierId == $consilier->id ? 'selected' : '' }}>
                            {{ $consilier->firstname }} {{ $consilier->lastname }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full bg-red-700 text-white px-4 py-2 rounded-md hover:bg-red-800 transition font-bold">
                    Aplica Filtre
                </button>
            </div>
        </form>
    </div>

    {{-- STATISTICI GENERALE --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-gradient-to-br from-red-700 to-red-900 text-white p-6 rounded-lg shadow-lg">
            <div class="text-xs font-bold uppercase opacity-90">Total Vehicule Vandute</div>
            <div class="text-4xl font-bold mt-2">{{ number_format($totalVehiclesVandute) }}</div>
            <div class="text-sm opacity-80 mt-1">{{ number_format($clientiUnici) }} clienti unici</div>
        </div>

        <div class="bg-gradient-to-br from-green-600 to-green-800 text-white p-6 rounded-lg shadow-lg">
            <div class="text-xs font-bold uppercase opacity-90">Valoare Totala Vanzari</div>
            <div class="text-4xl font-bold mt-2">€{{ number_format($totalValoareVanzari, 0, ',', '.') }}</div>
            <div class="text-sm opacity-80 mt-1">in perioada selectata</div>
        </div>

        <div class="bg-gradient-to-br from-blue-600 to-blue-800 text-white p-6 rounded-lg shadow-lg">
            <div class="text-xs font-bold uppercase opacity-90">Pret Mediu Vanzare</div>
            <div class="text-4xl font-bold mt-2">€{{ number_format($pretMediuVanzare, 0, ',', '.') }}</div>
            <div class="text-sm opacity-80 mt-1">per vehicul</div>
        </div>

        <div class="bg-gradient-to-br from-purple-600 to-purple-800 text-white p-6 rounded-lg shadow-lg">
            <div class="text-xs font-bold uppercase opacity-90">Discount Mediu</div>
            <div class="text-4xl font-bold mt-2">€{{ number_format($discountMediu, 0, ',', '.') }}</div>
            <div class="text-sm opacity-80 mt-1">per tranzactie</div>
        </div>
    </div>

    {{-- VANZARI PE CONSILIER --}}
    @if($vanzariPeConsilier->isNotEmpty())
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <h2 class="text-xl font-bold text-gray-800">Performanta pe Consilier</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Consilier</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Nr. Vanzari</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Valoare Totala</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Valoare Medie</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($vanzariPeConsilier as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item['consilier'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-700">{{ $item['nr_vanzari'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-green-600">€{{ number_format($item['valoare_totala'], 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-700">€{{ number_format($item['valoare_medie'], 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- VANZARI PE TIP CLIENT SI TOP MARCI --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        {{-- TIP CLIENT --}}
        @if($vanzariPeTipClient->isNotEmpty())
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200 bg-gray-50">
                <h2 class="text-xl font-bold text-gray-800">Vanzari pe Tip Client</h2>
            </div>
            <div class="p-6">
                @foreach($vanzariPeTipClient as $item)
                <div class="mb-4 last:mb-0">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-bold text-gray-700">{{ $item['tip'] }}</span>
                        <span class="text-sm text-gray-600">{{ $item['nr_vanzari'] }} vanzari ({{ $item['procent'] }}%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-red-700 h-3 rounded-full" style="width: {{ $item['procent'] }}%"></div>
                    </div>
                    <div class="text-right text-sm font-bold text-green-600 mt-1">€{{ number_format($item['valoare_totala'], 0, ',', '.') }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- TOP MARCI --}}
        @if($vanzariPeMarca->isNotEmpty())
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200 bg-gray-50">
                <h2 class="text-xl font-bold text-gray-800">Top 10 Marci</h2>
            </div>
            <div class="p-6">
                @foreach($vanzariPeMarca as $item)
                <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                    <span class="text-sm font-bold text-gray-700">{{ $item->marca }}</span>
                    <div class="text-right">
                        <div class="text-sm font-bold text-gray-900">{{ $item->nr_vanzari }} vanzari</div>
                        <div class="text-xs text-green-600">€{{ number_format($item->valoare_totala, 0, ',', '.') }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- TOP MODELE --}}
    @if($vanzariPeModel->isNotEmpty())
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <h2 class="text-xl font-bold text-gray-800">Top 15 Modele Vandute</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Model</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Vanzari</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Valoare Totala</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Pret Mediu</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($vanzariPeModel as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->model_complet }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-700">{{ $item->nr_vanzari }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-green-600">€{{ number_format($item->valoare_totala, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-700">€{{ number_format($item->pret_mediu, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- CARACTERISTICI TEHNICE --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        {{-- COMBUSTIBIL --}}
        @if($vanzariPeCombustibil->isNotEmpty())
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-bold text-gray-800">Combustibil</h2>
            </div>
            <div class="p-6">
                @foreach($vanzariPeCombustibil as $item)
                <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                    <span class="text-sm font-medium text-gray-700">{{ $item->fuel_type }}</span>
                    <span class="text-sm font-bold text-gray-900">{{ $item->nr_vanzari }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- TRANSMISIE --}}
        @if($vanzariPeTransmisie->isNotEmpty())
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-bold text-gray-800">Transmisie</h2>
            </div>
            <div class="p-6">
                @foreach($vanzariPeTransmisie as $item)
                <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                    <span class="text-sm font-medium text-gray-700">{{ $item->transmission }}</span>
                    <span class="text-sm font-bold text-gray-900">{{ $item->nr_vanzari }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- TRACTIUNE --}}
        @if($vanzariPeTractiune->isNotEmpty())
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-bold text-gray-800">Tractiune</h2>
            </div>
            <div class="p-6">
                @foreach($vanzariPeTractiune as $item)
                <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                    <span class="text-sm font-medium text-gray-700">{{ $item->traction }}</span>
                    <span class="text-sm font-bold text-gray-900">{{ $item->nr_vanzari }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- TOP CLIENTI --}}
    @if($topClienti->isNotEmpty())
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <h2 class="text-xl font-bold text-gray-800">Top 10 Clienti</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nume Client</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Tip</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Judet</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Achizitii</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Valoare Totala</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($topClienti as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item['nume'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <span class="px-2 py-1 text-xs font-bold rounded {{ $item['tip'] == 'PF' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                {{ $item['tip'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $item['judet'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-700">{{ $item['nr_achizitii'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-green-600">€{{ number_format($item['valoare_totala'], 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- ANALIZA GEOGRAFICA --}}
    @if($vanzariPeJudet->isNotEmpty())
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <h2 class="text-xl font-bold text-gray-800">Analiza Geografica - Top 15 Judete</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Judet</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Nr. Vanzari</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Valoare Totala</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($vanzariPeJudet as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->judet }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-700">{{ $item->nr_vanzari }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-green-600">€{{ number_format($item->valoare_totala, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- EVOLUTIE LUNARA --}}
    @if($evolutieVanzari->isNotEmpty())
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <h2 class="text-xl font-bold text-gray-800">Evolutie Lunara Vanzari</h2>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Luna</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Nr. Vanzari</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Valoare</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($evolutieVanzari as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->luna }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-700">{{ $item->nr_vanzari }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-green-600">€{{ number_format($item->valoare_totala, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    

    

</x-layout>