<x-layout>
    <div class="min-h-screen bg-gray-50 flex flex-col justify-center items-center p-6">

        <div class="bg-white rounded-xl shadow-2xl overflow-hidden max-w-md w-full border border-gray-200">

            {{-- Header Negru --}}
            <div class="bg-black p-6 text-center border-b-4 border-red-700">
                <h1 class="text-2xl font-extrabold text-white uppercase tracking-wider">
                    Asignare Vehicul
                </h1>
                <p class="text-gray-400 text-xs font-bold uppercase mt-2">
                    Consilier: <span class="text-white">{{ Auth::user()->name ?? (Auth::user()->firstname . ' ' . Auth::user()->lastname) }}</span>
                </p>
            </div>

            <div class="p-8">
                <div class="flex flex-col items-center mb-8">
                    <h2 class="text-xl font-black text-gray-900 text-center leading-tight">
                        {{ $vehicle->make->name }} {{ $vehicle->model->name }}
                    </h2>
                    <div class="mt-2 bg-gray-100 px-3 py-1 rounded text-xs font-mono text-gray-600 border border-gray-200 uppercase tracking-widest">
                        VIN: {{ $vehicle->vin }}
                    </div>
                    @if($vehicle->price)
                        <p class="mt-4 text-3xl font-bold text-gray-800 tracking-tight">
                            {{ number_format($vehicle->price, 0) }} €
                        </p>
                    @endif
                </div>

                <form action="{{ route('vehicles.processSale', $vehicle->id) }}" method="POST">
                    @csrf

                    {{-- ✅ AICI E MODIFICAREA: LISTA DE CLIENTI (DROPDOWN) --}}
                    <div class="mb-8">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Alege Clientul</label>
                        <div class="relative">
                            <select name="client_id" class="block w-full bg-gray-50 border border-gray-300 text-gray-900 py-3 px-4 pr-8 rounded-lg focus:outline-none focus:bg-white focus:border-red-500 font-bold">
                                <option value="">-- Selectează din listă --</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ (isset($selectedClient) && $selectedClient->id == $client->id) ? 'selected' : '' }}>
                                        {{ $client->lastname }} {{ $client->firstname }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ url()->previous() }}" class="flex items-center justify-center px-4 py-3 border-2 border-gray-200 text-gray-600 font-bold rounded-lg hover:border-black hover:text-black transition uppercase text-xs tracking-widest">
                            Renunță
                        </a>
                        <button type="submit" class="flex items-center justify-center px-4 py-3 bg-red-700 text-white font-bold rounded-lg hover:bg-red-800 transition shadow-lg uppercase text-xs tracking-widest">
                            Asignează
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
