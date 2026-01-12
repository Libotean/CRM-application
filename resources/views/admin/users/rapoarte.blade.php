<x-layout>
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Rapoarte si Statistici</h1>
        <p class="text-gray-600 mt-1">Analiza detaliata a vanzarilor si performantei (Baza de date: SQLite)</p>
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
                        <option value="{{ $consilier->id }}" @if($consilierId == $consilier->id) selected @endif>
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

        <div class="bg-gradient-to-br from-gray-600 to-gray-800 text-white p-6 rounded-lg shadow-lg">
            <div class="text-xs font-bold uppercase opacity-90">Conversie</div>
            <div class="text-4xl font-bold mt-2">{{ number_format($rataConversie, 1) }}%</div>
            <div class="text-sm opacity-80 mt-1">rata lead-uri castigate</div>
        </div>
    </div>

    {{-- VANZARI PE CONSILIER --}}
    @if(!empty($vanzariPeConsilier))
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

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        {{-- TOP MARCI --}}
        @if($vanzariPeMarca->isNotEmpty())
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200 bg-gray-50">
                <h2 class="text-xl font-bold text-gray-800">Top Marci Vandute</h2>
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

        {{-- EVOLUTIE LUNARA --}}
        @if($evolutieVanzari->isNotEmpty())
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200 bg-gray-50">
                <h2 class="text-xl font-bold text-gray-800">Evolutie Lunara Vanzari</h2>
            </div>
            <div class="p-6">
                @foreach($evolutieVanzari as $item)
                <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                    <span class="text-sm font-medium text-gray-700">{{ $item->luna }}</span>
                    <div class="flex items-center gap-4">
                        <span class="text-sm font-bold text-gray-900">{{ $item->nr_vanzari }} buc.</span>
                        <span class="text-sm font-bold text-green-600 w-24 text-right">€{{ number_format($item->valoare_totala, 0, ',', '.') }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- FR-19: RAPORT VECHIME IN STOC --}}
    @if($vehiculeVechime->isNotEmpty())
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <h2 class="text-xl font-bold text-gray-800">Raport Vechime în Stoc </h2>
            <p class="text-sm text-gray-600 mt-1">Vehicule în stoc sortate descrescător după vechime</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Marcă & Model</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Data Intrare</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Vechime (zile)</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Preț</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($vehiculeVechime as $vehicul)
                    <tr class="hover:bg-gray-50 @if($vehicul->vechime_zile > 90) bg-red-50 @elseif($vehicul->vechime_zile > 60) bg-yellow-50 @endif">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $vehicul->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $vehicul->marca }} {{ $vehicul->model }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $vehicul->data_intrare }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                            <span class="px-2 py-1 text-xs font-bold rounded @if($vehicul->vechime_zile > 90) bg-red-100 text-red-800 @elseif($vehicul->vechime_zile > 60) bg-yellow-100 text-yellow-800 @else bg-green-100 text-green-800 @endif">
                                {{ $vehicul->vechime_zile }} zile
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-gray-900">€{{ number_format($vehicul->pret, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="px-2 py-1 text-xs font-bold rounded bg-green-100 text-green-800">
                                {{ ucfirst($vehicul->status) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- FR-20: RAPORT INTERES CLIENTI --}}
    @if($interesVehicule->isNotEmpty())
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <h2 class="text-xl font-bold text-gray-800">Raport Interes Clienți per Vehicul </h2>
            <p class="text-sm text-gray-600 mt-1">Interacțiuni și Test-Drive-uri pentru vehiculele din stoc</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Marcă & Model</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Vechime</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Clienți Interesați</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Test-Drive-uri</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($interesVehicule as $v)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $v->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $v->marca }} {{ $v->model }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-700">{{ $v->vechime_zile }} zile</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                            <span class="px-3 py-1 text-sm font-bold rounded-full bg-blue-100 text-blue-800">{{ $v->nr_clienti }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                            <span class="px-3 py-1 text-sm font-bold rounded-full bg-purple-100 text-purple-800">{{ $v->nr_test_drives }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- FR-21: RAPORT ACTIVITATE CONSILIERI --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <h2 class="text-xl font-bold text-gray-800">Raport Activitate Consilieri </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Consilier</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Clienți Noi</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Interacțiuni</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">T-D Programate</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">T-D Efectuate</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Clienți Activi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($activitateConsilieri as $ac)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $ac->nume_consilier }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-blue-600">{{ $ac->clienti_noi }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">{{ $ac->interactiuni }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">{{ $ac->test_drives_programate }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-green-600">{{ $ac->test_drives_efectuate }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">{{ $ac->clienti_activi }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- FR-22: LEAD-URI SI CONVERSII --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <h2 class="text-xl font-bold text-gray-800">Lead-uri și Conversii </h2>
        </div>
        
        <div class="p-6 border-b border-gray-200">
            <form method="GET" action="{{ route('admin.rapoarte.index') }}" class="flex flex-wrap gap-3">
                <input type="hidden" name="start_date" value="{{ $startDate }}">
                <input type="hidden" name="end_date" value="{{ $endDate }}">
                <input type="hidden" name="consilier_id" value="{{ $consilierId }}">
                
                @foreach(['luna_curenta' => 'Luna Curentă', '3_luni' => '3 Luni', '6_luni' => '6 Luni', 'an' => 'Ultimul An'] as $val => $label)
                    <button type="submit" name="perioada_lead" value="{{ $val }}" 
                        class="px-4 py-2 rounded font-bold text-sm transition {{ $perioadaLead == $val ? 'bg-red-700 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </form>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="space-y-4">
                <div class="p-4 bg-red-50 rounded-lg border border-red-100">
                    <div class="text-xs text-red-600 font-bold uppercase">Rata de Conversie</div>
                    <div class="text-3xl font-bold text-red-700">{{ $rataConversie }}%</div>
                    <div class="text-xs text-gray-600 mt-1">{{ $leaduriCastigate }} castigate din {{ $totalLeaduri }} lead-uri</div>
                </div>
                
                <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                    <div class="text-xs text-blue-600 font-bold uppercase">Vanzari: Luna vs Luna Ant.</div>
                    <div class="text-2xl font-bold {{ $diferentaVanzari >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $diferentaVanzari >= 0 ? '+' : '' }}{{ $diferentaVanzari }} unitati
                    </div>
                    <div class="text-xs font-bold {{ $procentDiferenta >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $procentDiferenta }}% fata de luna trecuta
                    </div>
                </div>
            </div>

            <div class="md:col-span-2">
                <h3 class="text-sm font-bold text-gray-700 mb-4 uppercase">Distributie Lead-uri pe Status</h3>
                <div class="space-y-4">
                    @foreach($distributieLead as $item)
                    <div>
                        <div class="flex justify-between text-xs font-bold mb-1">
                            <span>{{ $item->status }}</span>
                            <span>{{ $item->total }} ({{ $item->procent }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-red-700 h-2 rounded-full" style="width: {{ $item->procent }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-layout>