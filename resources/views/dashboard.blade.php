<x-layout>
    <div class="mb-8 border-b border-gray-300 pb-4">
        <h1 class="text-3xl font-bold text-gray-900">
            Salut, <span class="text-red-700">{{ $user->firstname }}</span>!
        </h1>
        <p class="text-gray-500 mt-1">
            Rol activ: <span class="font-bold uppercase text-black">{{ $user->role == 'user' ? 'Consilier' : $user->role}}</span>
        </p>
    </div>

    @if ( $user->role == 'admin' )

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-red-700">
                <div class="text-gray-500 text-xs font-bold uppercase">Total Utilizatori</div>
                <div class="text-4xl font-bold text-gray-900 mt-2">
                    {{ \App\Models\User::count() }}
                </div>
                <div class="text-xs text-gray-400 mt-1">inregistrati in sistem</div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md border-l-4 border-black">
                <div class="text-gray-500 text-xs font-bold uppercase">Administratori</div>
                <div class="text-4xl font-bold text-gray-900 mt-2">
                    {{ \App\Models\User::where('role', 'admin')->count() }}
                </div>
            </div>

            <a href="{{ route('admin.users.create') }}" class="bg-black group p-6 rounded-lg shadow-md hover:bg-gray-900 transition flex flex-col justify-center text-center items-center cursor-pointer">
                <div class="bg-red-700 text-white p-3 rounded-full mb-2 group-hover:scale-110 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <div class="text-white font-bold uppercase text-sm">Creeaza Cont Nou</div>
            </a>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800">Panou de Control General</h2>
                <a href="{{ route('admin.users.index') }}" class="text-red-700 font-bold text-sm hover:underline">Vezi toti utilizatorii &rarr;</a>
            </div>
            <div class="p-6">
                <p class="text-gray-600">
                    Acesta este panoul administratorului. De aici poti gestiona accesul in aplicatie, poti reseta parole si poti vizualiza activitatea consilierilor.
                </p>
            </div>
        </div>

    @else

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <p class="text-gray-500 text-xs font-bold uppercase">Portofoliul Meu</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-1">
                    <span> {{ $user->clients->count() }}</span>
                    <span class="text-md text-gray-500"> clienti</span>
                </h3>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <p class="text-gray-500 text-xs font-bold uppercase">Lead-uri Active</p>
                <h3 class="text-3xl font-bold text-green-600 mt-1">
                    {{ $user->leads->where('is_completed', false)->count() }}
                </h3>
            </div>

            <a href="{{ route('consilier.clients.create') }}" class="bg-black group p-6 rounded-lg shadow-md hover:bg-gray-900 transition flex flex-col justify-center text-center items-center cursor-pointer">
                <div class="bg-red-700 text-white p-3 rounded-full mb-2 group-hover:scale-110 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <div class="text-white font-bold uppercase text-sm">Adaugare Client Nou</div>
            </a>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
            <div class="p-6 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                    Activitati Planificate
                </h2>
                <a href="{{ route('consilier.clients.index') }}" class="text-red-700 font-bold text-sm hover:underline">Vezi toti clientii &rarr;</a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Data si Ora</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Obiectiv</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Metoda</th>
                            <th class="px-6 py-3 text-right"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        
                        @forelse($user->leads->where('is_completed', false)->sortBy('appointment_date')->take(5) as $lead)
                            <tr class="hover:bg-yellow-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="font-bold text-gray-900">{{ $lead->appointment_date->format('d.m.Y') }}</span>
                                    <span class="text-gray-500 ml-1">{{ $lead->appointment_date->format('H:i') }}</span>

                                    @if($lead->appointment_date->isPast())
                                        <span class="ml-2 text-xs font-bold text-red-600 bg-red-100 px-2 py-0.5 rounded">INTARZIAT</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <a href="{{ route('consilier.clients.show', $lead->client->id) }}" class=" hover:underline"> {{ $lead->client->firstname }} {{ $lead->client->lastname }} </a>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $lead->objective }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $lead->method }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('consilier.clients.show', $lead->client->id) }}" class="text-red-600 hover:text-red-900 font-bold border border-gray-200 px-3 py-1 rounded hover:bg-gray-50 transition">
                                        Rezolva
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic">
                                    <p class="mb-2">Nu ai activitati in asteptare.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    @endif



</x-layout>
