<x-layout>
    <div class="container mx-auto p-6 max-w-5xl">

        <div class="flex justify-between items-center mb-8 border-b-2 border-black pb-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.users.index') }}" 
                   class="bg-black text-white px-6 py-3 rounded hover:bg-gray-800 transition font-bold text-lg">
                    ← Inapoi
                </a>
                <h1 class="text-3xl font-extrabold text-black uppercase tracking-tight">
                    Fisa Consilier
                </h1>
            </div>

            <a href="{{ route('admin.users.edit', $user->id) }}" 
               class="bg-red-700 text-white px-8 py-3 rounded hover:bg-red-800 transition font-bold text-lg shadow-sm">
                EDITEAZA DATELE
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-8">
                
                <div class="bg-white border border-gray-300 p-6">
                    <h2 class="text-xl font-bold text-red-700 mb-6 uppercase border-b border-gray-200 pb-2">
                        Date Personale
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-sm font-bold text-gray-500 uppercase mb-1">Nume Complet</label>
                            <p class="text-2xl font-bold text-black">{{ $user->lastname }} {{ $user->firstname }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-500 uppercase mb-1">Email</label>
                            <p class="text-xl font-medium text-black">{{ $user->email }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-500 uppercase mb-1">Rol Sistem</label>
                            <p class="text-lg font-bold text-black uppercase">
                                {{ $user->role == 'admin' ? 'Administrator' : 'Consilier Vânzări' }}
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-500 uppercase mb-1">Locatie</label>
                            <p class="text-lg text-black">{{ $user->locality }}, {{ $user->county }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-300 p-6">
                    <h2 class="text-xl font-bold text-red-700 mb-6 uppercase border-b border-gray-200 pb-2">
                        Detalii Contractuale
                    </h2>
                    <div class="flex gap-12">
                        <div>
                            <label class="block text-sm font-bold text-gray-500 uppercase mb-1">Data Angajarii</label>
                            <p class="text-xl font-bold text-black">
                                {{ $user->date_start ? \Carbon\Carbon::parse($user->date_start)->format('d.m.Y') : '-' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-500 uppercase mb-1">Data Expirarii</label>
                            <p class="text-xl font-bold {{ $user->date_end && \Carbon\Carbon::parse($user->date_end)->isPast() ? 'text-red-600' : 'text-black' }}">
                                {{ $user->date_end ? \Carbon\Carbon::parse($user->date_end)->format('d.m.Y') : 'Nedeterminat' }}
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="lg:col-span-1">
                <div class="bg-black text-white p-6 h-full">
                    <h2 class="text-xl font-bold text-white mb-6 uppercase border-b border-gray-600 pb-2">
                        Performanta
                    </h2>

                    <div class="mb-8 text-center border border-gray-700 p-4">
                        <span class="block text-5xl font-extrabold text-red-600 mb-2">
                            {{ $user->clients->count() }}
                        </span>
                        <span class="text-sm font-bold uppercase tracking-wider text-gray-300">Total Clienti</span>
                    </div>

                    <div class="mb-8 text-center border border-gray-700 p-4">
                        <span class="block text-4xl font-bold text-white mb-2">
                            {{ $user->clients->where('status', 'activ')->count() }}
                        </span>
                        <span class="text-sm font-bold uppercase tracking-wider text-gray-300">Clienti Activi</span>
                    </div>

                    <div class="mt-8">
                        <p class="text-xs text-gray-400 uppercase mb-4 font-bold">Ultimii Clienti Adaugati:</p>
                        <ul class="space-y-3">
                            @forelse($user->clients->sortByDesc('created_at')->take(3) as $client)
                                <li class="border-b border-gray-800 pb-2">
                                    <span class="block font-bold text-white">{{ $client->full_name }}</span>
                                    <span class="text-xs text-gray-400">{{ $client->created_at->format('d.m.Y') }}</span>
                                </li>
                            @empty
                                <li class="text-gray-500 italic">Niciun client recent.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-layout>