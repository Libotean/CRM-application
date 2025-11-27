<x-layout>
    <div class="py-12 flex justify-center">
        <div class="w-full max-w-2xl">

            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-8">

                <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-4">
                    💸 Finalizare Vânzare
                </h2>

                <div class="mb-6">
                    <p class="text-sm text-gray-500">Consilier Vânzări:</p>
                    <p class="font-bold text-gray-800 text-lg">
                        {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}
                    </p>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8 flex items-center gap-4">
                    <div class="text-4xl">🚗</div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-900">
                            {{ $vehicle->make->name }} {{ $vehicle->model->name }}
                        </h3>
                        <p class="text-blue-700 font-bold mt-1">
                            Preț Final: {{ number_format($vehicle->price_eur, 0, '.', '.') }} €
                        </p>
                    </div>
                </div>

                <form action="{{ route('vehicles.processSale', $vehicle->id) }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label class="block font-medium text-sm text-gray-700 mb-2">Selectează Clientul</label>
                        <select name="client_id" class="w-full border-gray-300 rounded-md shadow-sm p-2 border" required>
                            <option value="">-- Alege din listă --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">
                                    {{ $client->lastname }} {{ $client->firstname }}
                                    ({{ $client->cui ? 'CUI: '.$client->cui : 'CNP: '.$client->cnp }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-between items-center mt-8">
                        <a href="{{ route('vehicles.index') }}" class="text-gray-600 font-bold hover:underline">
                            ← Renunță
                        </a>
                        <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-green-700 transition shadow-md">
                            ✅ Confirmă Vânzarea
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-layout>
