<x-layout>
    <!-- Header Section -->
    <div class="mb-8 border-b border-gray-300 pb-4">
        <h1 class="text-3xl font-bold text-gray-900">
            Bine ai venit, <span class="text-red-700">{{ Auth::user()->firstname }}</span>!
        </h1>
        <p class="text-gray-500 mt-1">
            🚗 Gestiune Clienți - <span class="font-bold uppercase text-black">ATP MOTORS</span> Baia Mare
        </p>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-8">
        <div class="flex">
            <div class="ml-3">
                <p class="text-sm text-green-700">
                    <span class="font-bold">✓</span> {{ session('success') }}
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Table Section -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-8">
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <h2 class="text-xl font-bold text-gray-800">👥 Lista Clienților</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Prenume</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nume</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">CNP</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Telefon</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Localitate</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Actiuni</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($client as $c)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $c->firstname }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $c->lastname }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $c->cnp ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $c->email }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $c->phone }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $c->locality ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm">
                            @if(strtolower($c->status) === 'activ')
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-green-100 text-green-800">
                                    {{ ucfirst($c->status) }}
                                </span>
                            @else
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide bg-gray-100 text-gray-800">
                                    {{ ucfirst($c->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('consilier.store', $c->id) }}" class="text-red-700 hover:text-red-900 font-bold mr-3 transition">
                                Detalii
                            </a>
                            <a href="{{ route('consilier.index', $c->id) }}" class="text-gray-400 hover:text-gray-600 transition">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <h2 class="text-xl font-bold text-gray-800">➕ Adaugă Client Nou</h2>
        </div>
        
        <div class="p-6">
            <form action="{{ route('consilier.store') }}" method="POST">
                @csrf
                
                <!-- Informații Personale -->
                <h3 class="text-lg font-bold text-gray-800 mb-4 mt-2">Informații Personale</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    <div class="flex flex-col gap-2">
                        <label for="firstname" class="text-gray-700 font-bold text-sm">Prenume *</label>
                        <input 
                            type="text" 
                            id="firstname"
                            name="firstname" 
                            placeholder="Introduceți prenumele"
                            value="{{ old('firstname') }}"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm transition-all duration-200 focus:outline-none focus:border-red-700 focus:ring-2 focus:ring-red-100"
                            required
                        >
                        @error('firstname')
                            <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-2">
                        <label for="lastname" class="text-gray-700 font-bold text-sm">Nume *</label>
                        <input 
                            type="text" 
                            id="lastname"
                            name="lastname" 
                            placeholder="Introduceți numele"
                            value="{{ old('lastname') }}"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm transition-all duration-200 focus:outline-none focus:border-red-700 focus:ring-2 focus:ring-red-100"
                            required
                        >
                        @error('lastname')
                            <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-2">
                        <label for="type" class="text-gray-700 font-bold text-sm">Tip Client *</label>
                        <select 
                            id="type"
                            name="type"
                            required
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-red-700 focus:ring-2 focus:ring-red-100 bg-white"
                        >
                            <option value="fizic">Persoană Fizică</option>
                            <option value="juridic">Persoană Juridică</option>
                        </select>

                        @error('type')
                            <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-2">
                        <label for="cnp" class="text-gray-700 font-bold text-sm">CNP *</label>
                        <input 
                            type="text" 
                            id="cnp"
                            name="cnp" 
                            placeholder="1234567890123"
                            value="{{ old('cnp') }}"
                            maxlength="13"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm transition-all duration-200 focus:outline-none focus:border-red-700 focus:ring-2 focus:ring-red-100"
                            required
                        >
                        @error('cnp')
                            <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Informații Fiscale -->
                <h3 class="text-lg font-bold text-gray-800 mb-4 mt-6">Informații Fiscale</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    <div class="flex flex-col gap-2">
                        <label for="cui" class="text-gray-700 font-bold text-sm">CUI *</label>
                        <input 
                            type="text" 
                            id="cui"
                            name="cui" 
                            placeholder="RO12345678"
                            value="{{ old('cui') }}"
                            maxlength="12"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm transition-all duration-200 focus:outline-none focus:border-red-700 focus:ring-2 focus:ring-red-100"
                            required
                        >
                        @error('cui')
                            <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-2">
                        <label for="tva_payer" class="text-gray-700 font-bold text-sm">Plătitor TVA *</label>
                        <select 
                            id="tva_payer"
                            name="tva_payer" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm transition-all duration-200 focus:outline-none focus:border-red-700 focus:ring-2 focus:ring-red-100 bg-white"
                            required
                        >
                            <option value="">Selectează</option>
                            <option value="1" {{ old('tva_payer') == '1' ? 'selected' : '' }}>Da</option>
                            <option value="0" {{ old('tva_payer') == '0' ? 'selected' : '' }}>Nu</option>
                        </select>
                        @error('tva_payer')
                            <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Contact -->
                <h3 class="text-lg font-bold text-gray-800 mb-4 mt-6">Informații de Contact</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    <div class="flex flex-col gap-2">
                        <label for="email" class="text-gray-700 font-bold text-sm">Email *</label>
                        <input 
                            type="email" 
                            id="email"
                            name="email" 
                            placeholder="exemplu@email.com"
                            value="{{ old('email') }}"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm transition-all duration-200 focus:outline-none focus:border-red-700 focus:ring-2 focus:ring-red-100"
                            required
                        >
                        @error('email')
                            <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-2">
                        <label for="phone" class="text-gray-700 font-bold text-sm">Telefon</label>
                        <input 
                            type="text" 
                            id="phone"
                            name="phone" 
                            placeholder="07XX XXX XXX"
                            value="{{ old('phone') }}"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm transition-all duration-200 focus:outline-none focus:border-red-700 focus:ring-2 focus:ring-red-100"
                        >
                        @error('phone')
                            <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Adresă -->
                <h3 class="text-lg font-bold text-gray-800 mb-4 mt-6">Adresă</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    <div class="flex flex-col gap-2">
                        <label for="country" class="text-gray-700 font-bold text-sm">Țară</label>
                        <input 
                            type="text" 
                            id="country"
                            name="country" 
                            placeholder="România"
                            value="{{ old('country') }}"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm transition-all duration-200 focus:outline-none focus:border-red-700 focus:ring-2 focus:ring-red-100"
                        >
                        @error('country')
                            <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-2">
                        <label for="county" class="text-gray-700 font-bold text-sm">Județ</label>
                        <input 
                            type="text" 
                            id="county"
                            name="county" 
                            placeholder="Maramureș"
                            value="{{ old('county') }}"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm transition-all duration-200 focus:outline-none focus:border-red-700 focus:ring-2 focus:ring-red-100"
                        >
                        @error('county')
                            <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-2">
                        <label for="locality" class="text-gray-700 font-bold text-sm">Localitate</label>
                        <input 
                            type="text" 
                            id="locality"
                            name="locality" 
                            placeholder="Baia Mare"
                            value="{{ old('locality') }}"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm transition-all duration-200 focus:outline-none focus:border-red-700 focus:ring-2 focus:ring-red-100"
                        >
                        @error('locality')
                            <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-2 md:col-span-2 lg:col-span-3">
                        <label for="address" class="text-gray-700 font-bold text-sm">Adresă Completă</label>
                        <input 
                            type="text" 
                            id="address"
                            name="address" 
                            placeholder="Strada, Număr, Bloc, Scara, Etaj, Apartament"
                            value="{{ old('address') }}"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm transition-all duration-200 focus:outline-none focus:border-red-700 focus:ring-2 focus:ring-red-100"
                        >
                        @error('address')
                            <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <h3 class="text-lg font-bold text-gray-800 mb-4 mt-6">Status Client</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    <div class="flex flex-col gap-2">
                        <label for="status" class="text-gray-700 font-bold text-sm">Status *</label>
                        <select 
                            id="status"
                            name="status" 
                            class="px-4 py-2 border border-gray-300 rounded-lg text-sm transition-all duration-200 focus:outline-none focus:border-red-700 focus:ring-2 focus:ring-red-100 bg-white"
                            required
                        >
                            <option value="activ" {{ old('status') === 'activ' ? 'selected' : '' }}>Activ</option>
                            <option value="inactiv" {{ old('status') === 'inactiv' ? 'selected' : '' }}>Inactiv</option>
                        </select>
                        @error('status')
                            <span class="text-red-600 text-xs mt-1">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8">
                    <button 
                        type="submit" 
                        class="bg-black text-white px-8 py-3 rounded-lg text-sm font-bold uppercase tracking-wider shadow-md hover:bg-gray-900 transition-all duration-200"
                    >
                        Adaugă Client
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>