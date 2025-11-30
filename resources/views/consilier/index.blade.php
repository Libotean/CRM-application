<x-layout>
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-900 via-blue-700 to-cyan-500 p-8 rounded-2xl shadow-2xl mb-8 border-l-8 border-orange-500">
        <h1 class="text-4xl font-bold text-white mb-2">
            Bine ai venit, <span class="text-orange-400">{{ Auth::user()->firstname }}</span>!
        </h1>
        <p class="text-gray-100 text-lg tracking-wide">
            🚗 Gestiune Clienți - <span class="font-black text-orange-400 tracking-wider">ATP MOTORS</span> Baia Mare
        </p>
    </div>

    @if(session('success'))
    <div class="bg-gradient-to-r from-green-500 to-green-400 text-white p-4 rounded-xl shadow-lg mb-6 flex items-center gap-3">
        <span class="text-2xl font-bold">✓</span>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <!-- Table Section -->
    <div class="bg-white/95 p-8 rounded-2xl shadow-2xl mb-8">
        <h2 class="text-3xl font-bold text-blue-900 mb-6 pb-4 border-b-4 border-orange-500 flex items-center gap-3">
            <span class="text-2xl">👥</span>
            Lista Clienților
        </h2>
        
        <div class="overflow-x-auto rounded-xl shadow-lg">
            <table class="w-full border-collapse bg-white">
                <thead class="bg-gradient-to-r from-blue-900 to-cyan-500">
                    <tr>
                        <th class="px-6 py-4 text-left text-white font-semibold uppercase tracking-wider text-sm">Prenume</th>
                        <th class="px-6 py-4 text-left text-white font-semibold uppercase tracking-wider text-sm">Nume</th>
                        <th class="px-6 py-4 text-left text-white font-semibold uppercase tracking-wider text-sm">CNP</th>
                        <th class="px-6 py-4 text-left text-white font-semibold uppercase tracking-wider text-sm">Email</th>
                        <th class="px-6 py-4 text-left text-white font-semibold uppercase tracking-wider text-sm">Telefon</th>
                        <th class="px-6 py-4 text-left text-white font-semibold uppercase tracking-wider text-sm">Localitate</th>
                        <th class="px-6 py-4 text-left text-white font-semibold uppercase tracking-wider text-sm">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clients as $c)
                    <tr class="border-b border-gray-200 hover:bg-gradient-to-r hover:from-gray-50 hover:to-gray-100 transition-all duration-300 hover:translate-x-1">
                        <td class="px-6 py-4 text-gray-800">{{ $c->firstname }}</td>
                        <td class="px-6 py-4 text-gray-800">{{ $c->lastname }}</td>
                        <td class="px-6 py-4 text-gray-800">{{ $c->cnp ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-800">{{ $c->email }}</td>
                        <td class="px-6 py-4 text-gray-800">{{ $c->phone }}</td>
                        <td class="px-6 py-4 text-gray-800">{{ $c->locality ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if(strtolower($c->status) === 'activ')
                                <span class="inline-block px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wide bg-gradient-to-r from-green-500 to-green-400 text-white shadow-md">
                                    {{ ucfirst($c->status) }}
                                </span>
                            @else
                                <span class="inline-block px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wide bg-gradient-to-r from-gray-400 to-gray-500 text-white shadow-md">
                                    {{ ucfirst($c->status) }}
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Form Section -->
    <div class="bg-white/95 p-8 rounded-2xl shadow-2xl">
        <h2 class="text-3xl font-bold text-blue-900 mb-6 pb-4 border-b-4 border-orange-500 flex items-center gap-3">
            <span class="text-2xl">➕</span>
            Adaugă Client Nou
        </h2>
        
        <form action="{{ route('consilier.store') }}" method="POST">
            @csrf
            
            <!-- Informații Personale -->
            <h3 class="text-xl font-semibold text-blue-800 mb-4 mt-6">Informații Personale</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div class="flex flex-col gap-2">
                    <label for="firstname" class="text-blue-900 font-semibold text-sm">Prenume *</label>
                    <input 
                        type="text" 
                        id="firstname"
                        name="firstname" 
                        placeholder="Introduceți prenumele"
                        value="{{ old('firstname') }}"
                        class="px-5 py-3 border-2 border-gray-300 rounded-lg text-base transition-all duration-300 focus:outline-none focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100"
                        required
                    >
                    @error('firstname')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col gap-2">
                    <label for="lastname" class="text-blue-900 font-semibold text-sm">Nume *</label>
                    <input 
                        type="text" 
                        id="lastname"
                        name="lastname" 
                        placeholder="Introduceți numele"
                        value="{{ old('lastname') }}"
                        class="px-5 py-3 border-2 border-gray-300 rounded-lg text-base transition-all duration-300 focus:outline-none focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100"
                        required
                    >
                    @error('lastname')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col gap-2">
                    <label for="cnp" class="text-blue-900 font-semibold text-sm">CNP *</label>
                    <input 
                        type="text" 
                        id="cnp"
                        name="cnp" 
                        placeholder="1234567890123"
                        value="{{ old('cnp') }}"
                        maxlength="13"
                        class="px-5 py-3 border-2 border-gray-300 rounded-lg text-base transition-all duration-300 focus:outline-none focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100"
                        required
                    >
                    @error('cnp')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Informații Fiscale -->
            <h3 class="text-xl font-semibold text-blue-800 mb-4 mt-6">Informații Fiscale</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div class="flex flex-col gap-2">
                    <label for="cui" class="text-blue-900 font-semibold text-sm">CUI *</label>
                    <input 
                        type="text" 
                        id="cui"
                        name="cui" 
                        placeholder="RO12345678"
                        value="{{ old('cui') }}"
                        maxlength="12"
                        class="px-5 py-3 border-2 border-gray-300 rounded-lg text-base transition-all duration-300 focus:outline-none focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100"
                        required
                    >
                    @error('cui')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col gap-2">
                    <label for="tva_payer" class="text-blue-900 font-semibold text-sm">Plătitor TVA *</label>
                    <select 
                        id="tva_payer"
                        name="tva_payer" 
                        class="px-5 py-3 border-2 border-gray-300 rounded-lg text-base transition-all duration-300 focus:outline-none focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100 bg-white"
                        required
                    >
                        <option value="">Selectează</option>
                        <option value="1" {{ old('tva_payer') == '1' ? 'selected' : '' }}>Da</option>
                        <option value="0" {{ old('tva_payer') == '0' ? 'selected' : '' }}>Nu</option>
                    </select>
                    @error('tva_payer')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Contact -->
            <h3 class="text-xl font-semibold text-blue-800 mb-4 mt-6">Informații de Contact</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div class="flex flex-col gap-2">
                    <label for="email" class="text-blue-900 font-semibold text-sm">Email *</label>
                    <input 
                        type="email" 
                        id="email"
                        name="email" 
                        placeholder="exemplu@email.com"
                        value="{{ old('email') }}"
                        class="px-5 py-3 border-2 border-gray-300 rounded-lg text-base transition-all duration-300 focus:outline-none focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100"
                        required
                    >
                    @error('email')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col gap-2">
                    <label for="phone" class="text-blue-900 font-semibold text-sm">Telefon</label>
                    <input 
                        type="text" 
                        id="phone"
                        name="phone" 
                        placeholder="07XX XXX XXX"
                        value="{{ old('phone') }}"
                        class="px-5 py-3 border-2 border-gray-300 rounded-lg text-base transition-all duration-300 focus:outline-none focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100"
                    >
                    @error('phone')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Adresă -->
            <h3 class="text-xl font-semibold text-blue-800 mb-4 mt-6">Adresă</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div class="flex flex-col gap-2">
                    <label for="country" class="text-blue-900 font-semibold text-sm">Țară</label>
                    <input 
                        type="text" 
                        id="country"
                        name="country" 
                        placeholder="România"
                        value="{{ old('country') }}"
                        class="px-5 py-3 border-2 border-gray-300 rounded-lg text-base transition-all duration-300 focus:outline-none focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100"
                    >
                    @error('country')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col gap-2">
                    <label for="county" class="text-blue-900 font-semibold text-sm">Județ</label>
                    <input 
                        type="text" 
                        id="county"
                        name="county" 
                        placeholder="Maramureș"
                        value="{{ old('county') }}"
                        class="px-5 py-3 border-2 border-gray-300 rounded-lg text-base transition-all duration-300 focus:outline-none focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100"
                    >
                    @error('county')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col gap-2">
                    <label for="locality" class="text-blue-900 font-semibold text-sm">Localitate</label>
                    <input 
                        type="text" 
                        id="locality"
                        name="locality" 
                        placeholder="Baia Mare"
                        value="{{ old('locality') }}"
                        class="px-5 py-3 border-2 border-gray-300 rounded-lg text-base transition-all duration-300 focus:outline-none focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100"
                    >
                    @error('locality')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col gap-2 md:col-span-2 lg:col-span-3">
                    <label for="address" class="text-blue-900 font-semibold text-sm">Adresă Completă</label>
                    <input 
                        type="text" 
                        id="address"
                        name="address" 
                        placeholder="Strada, Număr, Bloc, Scara, Etaj, Apartament"
                        value="{{ old('address') }}"
                        class="px-5 py-3 border-2 border-gray-300 rounded-lg text-base transition-all duration-300 focus:outline-none focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100"
                    >
                    @error('address')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Status -->
            <h3 class="text-xl font-semibold text-blue-800 mb-4 mt-6">Status Client</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div class="flex flex-col gap-2">
                    <label for="status" class="text-blue-900 font-semibold text-sm">Status *</label>
                    <select 
                        id="status"
                        name="status" 
                        class="px-5 py-3 border-2 border-gray-300 rounded-lg text-base transition-all duration-300 focus:outline-none focus:border-cyan-500 focus:ring-4 focus:ring-cyan-100 bg-white"
                        required
                    >
                        <option value="activ" {{ old('status') === 'activ' ? 'selected' : '' }}>Activ</option>
                        <option value="inactiv" {{ old('status') === 'inactiv' ? 'selected' : '' }}>Inactiv</option>
                    </select>
                    @error('status')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-8">
                <button 
                    type="submit" 
                    class="bg-gradient-to-r from-orange-500 to-orange-400 text-white px-10 py-4 rounded-lg text-lg font-bold uppercase tracking-wider shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 active:translate-y-0"
                >
                    Adaugă Client
                </button>
            </div>
        </form>
    </div>
</x-layout>