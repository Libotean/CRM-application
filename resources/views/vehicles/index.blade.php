<x-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6 flex justify-between items-center">
                <h2 class="text-3xl font-bold text-white">
                    Parc Auto - Stoc Vehicule
                </h2>
                <a href="/" class="bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 font-bold transition">
                    ← Înapoi la Dashboard
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($vehicles as $car)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 hover:shadow-md transition duration-300">
                        <div class="p-6">

                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">
                                        {{ $car->make->name }} {{ $car->model->name }}
                                    </h3>
                                    <p class="text-sm text-gray-500">{{ $car->version_name }}</p>
                                </div>
                                <span class="bg-gray-100 text-gray-600 text-xs font-bold px-2 py-1 rounded">
                                    {{ $car->model->category->name ?? 'Auto' }}
                                </span>
                            </div>

                            <div class="text-sm text-gray-600 space-y-1 mb-4">
                                <p>VIN: <span class="font-mono text-gray-800">{{ $car->vin ?? '-' }}</span></p>
                                <p>Preț: <span class="text-lg font-bold text-blue-700">{{ number_format($car->price_eur, 0, '.', '.') }} €</span></p>
                            </div>

                            <div class="border-t pt-4 mt-2">
                                @if($car->client_id)
                                    <div class="bg-red-50 border border-red-100 p-2 rounded text-center">
                                        <span class="block text-xs font-bold text-red-600 uppercase">Vândută către</span>
                                        <span class="text-sm font-semibold text-gray-800">
                                            {{ $car->client->lastname }} {{ $car->client->firstname }}
                                        </span>
                                    </div>
                                @else
                                    <div class="flex justify-between items-center">
                                        <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded">✅ ÎN STOC</span>
                                        <a href="{{ route('vehicles.sell', $car->id) }}" class="bg-blue-700 text-white px-3 py-2 rounded-lg text-xs uppercase font-bold hover:bg-blue-800 transition">
                                            Asignează Client ➜
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
