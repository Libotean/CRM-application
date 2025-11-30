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

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 opacity-75">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <p class="text-gray-500 text-xs font-bold uppercase">Portofoliul Meu</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-1">--</h3>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <p class="text-gray-500 text-xs font-bold uppercase">Lead-uri Active</p>
                <h3 class="text-3xl font-bold text-green-600 mt-1">--</h3>
            </div>

            <div class="bg-gray-200 p-6 rounded-lg border border-gray-300 flex items-center justify-center text-center">
                <span class="text-gray-500 font-bold uppercase tracking-wide">Adauga Client (Indisponibil)</span>
            </div>
        </div>

        <h2 class="text-xl font-bold text-gray-800 mb-4 opacity-75">Clienti Recenti</h2>
        <div class="bg-white rounded-lg shadow overflow-hidden opacity-75">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nume</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Data</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">Exemplu Client 1</td>
                        <td class="px-6 py-4 text-sm text-gray-500">Activ</td>
                        <td class="px-6 py-4 text-sm text-gray-500">--.--.----</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">Exemplu Client 2</td>
                        <td class="px-6 py-4 text-sm text-gray-500">Inactiv</td>
                        <td class="px-6 py-4 text-sm text-gray-500">--.--.----</td>
                    </tr>
                </tbody>
            </table>
        </div>

    @endif
</x-layout>