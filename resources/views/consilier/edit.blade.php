<x-layout>
    <div class="container mx-auto max-w-4xl">

        <div class="flex justify-between items-center mb-8 border-b-2 border-black pb-4">
            <div>
                <h1 class="text-3xl font-extrabold text-black uppercase tracking-tight">
                    Editeaza Client
                </h1>
                <p class="text-gray-500 text-sm mt-1">
                    Modifici datele pentru: <span class="font-bold text-black">{{ $client->full_name }}</span>
                </p>
            </div>
            
            <a href="{{ route('consilier.clients.index') }}" class="text-gray-500 hover:text-black font-bold transition flex items-center">
                <span class="mr-2">←</span> Inapoi
            </a>
        </div>

        <form action="{{ route('consilier.clients.update', $client->id) }}" method="POST" class="bg-white p-8 rounded shadow-md border border-gray-200">
            @csrf
            @method('PUT')
            
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-700 text-red-800 p-4 rounded mb-8 flex items-start">
                    <svg class="w-6 h-6 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <div>
                        <p class="font-bold uppercase text-xs tracking-wider mb-1">Atentie</p>
                        <p class="text-sm">Va rugam sa corectati erorile marcate mai jos.</p>
                    </div>
                </div>
            @endif

            <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6 border-b border-gray-100 pb-2">
                1. Tip Client
            </h2>

            <div class="mb-8">
                <label for="type" class="block text-gray-800 text-sm font-bold mb-2 uppercase">Tip *</label>
                <select name="type" id="type"
                        class="w-full border-2 rounded px-4 py-3 focus:outline-none transition font-medium bg-white
                        @error('type') border-red-600 focus:border-red-700 @else border-gray-300 focus:border-black @enderror"
                        required>
                    <option value="persoana_fizica" {{ old('type', $client->type) == 'persoana_fizica' ? 'selected' : '' }}>Persoana Fizica</option>
                    <option value="persoana_juridica" {{ old('type', $client->type) == 'persoana_juridica' ? 'selected' : '' }}>Persoana Juridica</option>
                </select>
                @error('type') <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
            </div>

            <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6 border-b border-gray-100 pb-2">
                2. Informatii Identificare
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label for="lastname" class="block text-gray-800 text-sm font-bold mb-2 uppercase">Nume de familie *</label>
                    <input type="text" name="lastname" id="lastname"
                           class="w-full border-2 rounded px-4 py-3 focus:outline-none transition font-medium
                           @error('lastname') border-red-600 focus:border-red-700 text-red-900 @else border-gray-300 focus:border-black text-gray-900 @enderror"
                           value="{{ old('lastname', $client->lastname) }}" required>
                    @error('lastname') <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="firstname" class="block text-gray-800 text-sm font-bold mb-2 uppercase">Prenume *</label>
                    <input type="text" name="firstname" id="firstname"
                           class="w-full border-2 rounded px-4 py-3 focus:outline-none transition font-medium
                           @error('firstname') border-red-600 focus:border-red-700 text-red-900 @else border-gray-300 focus:border-black text-gray-900 @enderror"
                           value="{{ old('firstname', $client->firstname) }}" required>
                    @error('firstname') <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div id="cnp_display">
                    <label for="cnp" class="block text-gray-800 text-sm font-bold mb-2 uppercase">
                        CNP <span class="text-gray-400 font-normal lowercase">(pentru PF)</span>
                    </label>
                    <input type="text" name="cnp" id="cnp"
                           class="w-full border-2 rounded px-4 py-3 focus:outline-none transition font-medium
                           @error('cnp') border-red-600 focus:border-red-700 text-red-900 @else border-gray-300 focus:border-black text-gray-900 @enderror"
                           value="{{ old('cnp', $client->cnp) }}">
                    @error('cnp') <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div id="cui_display">
                    <label for="cui" class="block text-gray-800 text-sm font-bold mb-2 uppercase">
                        CUI <span class="text-gray-400 font-normal lowercase">(pentru PJ)</span>
                    </label>
                    <input type="text" name="cui" id="cui"
                           class="w-full border-2 rounded px-4 py-3 focus:outline-none transition font-medium
                           @error('cui') border-red-600 focus:border-red-700 text-red-900 @else border-gray-300 focus:border-black text-gray-900 @enderror"
                           value="{{ old('cui', $client->cui) }}">
                    @error('cui') <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="tva_payer" class="block text-gray-800 text-sm font-bold mb-2 uppercase">Platitor TVA *</label>
                    <select name="tva_payer" id="tva_payer"
                            class="w-full border-2 rounded px-4 py-3 focus:outline-none transition font-medium bg-white
                            @error('tva_payer') border-red-600 focus:border-red-700 @else border-gray-300 focus:border-black @enderror"
                            required>
                        <option value="0" {{ old('tva_payer', $client->tva_payer) == '0' ? 'selected' : '' }}>Nu</option>
                        <option value="1" {{ old('tva_payer', $client->tva_payer) == '1' ? 'selected' : '' }}>Da</option>
                    </select>
                    @error('tva_payer') <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="status" class="block text-gray-800 text-sm font-bold mb-2 uppercase">Status *</label>
                    <select name="status" id="status"
                            class="w-full border-2 rounded px-4 py-3 focus:outline-none transition font-medium bg-white
                            @error('status') border-red-600 focus:border-red-700 @else border-gray-300 focus:border-black @enderror"
                            required>
                        <option value="activ" {{ old('status', $client->status) == 'activ' ? 'selected' : '' }}>Activ</option>
                        <option value="inactiv" {{ old('status', $client->status) == 'inactiv' ? 'selected' : '' }}>Inactiv</option>
                    </select>
                    @error('status') <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>
            </div>

            <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6 border-b border-gray-100 pb-2">
                3. Informatii Contact
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label for="email" class="block text-gray-800 text-sm font-bold mb-2 uppercase">Email *</label>
                    <input type="email" name="email" id="email"
                           class="w-full border-2 rounded px-4 py-3 focus:outline-none transition font-medium
                           @error('email') border-red-600 focus:border-red-700 text-red-900 @else border-gray-300 focus:border-black text-gray-900 @enderror"
                           value="{{ old('email', $client->email) }}" required>
                    @error('email') <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="phone" class="block text-gray-800 text-sm font-bold mb-2 uppercase">Telefon *</label>
                    <input type="text" name="phone" id="phone"
                           class="w-full border-2 rounded px-4 py-3 focus:outline-none transition font-medium
                           @error('phone') border-red-600 focus:border-red-700 text-red-900 @else border-gray-300 focus:border-black text-gray-900 @enderror"
                           value="{{ old('phone', $client->phone) }}" required>
                    @error('phone') <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>
            </div>

            <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6 border-b border-gray-100 pb-2">
                4. Locatie & Adresa
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label for="country" class="block text-gray-800 text-sm font-bold mb-2 uppercase">Tara *</label>
                    <input type="text" name="country" id="country"
                           class="w-full border-2 rounded px-4 py-3 focus:outline-none transition font-medium
                           @error('country') border-red-600 focus:border-red-700 @else border-gray-300 focus:border-black @enderror"
                           value="{{ old('country', $client->country) }}" required>
                    @error('country') <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="county" class="block text-gray-800 text-sm font-bold mb-2 uppercase">Judet *</label>
                    <input type="text" name="county" id="county"
                           class="w-full border-2 rounded px-4 py-3 focus:outline-none transition font-medium
                           @error('county') border-red-600 focus:border-red-700 @else border-gray-300 focus:border-black @enderror"
                           value="{{ old('county', $client->county) }}" required>
                    @error('county') <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="locality" class="block text-gray-800 text-sm font-bold mb-2 uppercase">Localitate *</label>
                    <input type="text" name="locality" id="locality"
                           class="w-full border-2 rounded px-4 py-3 focus:outline-none transition font-medium
                           @error('locality') border-red-600 focus:border-red-700 @else border-gray-300 focus:border-black @enderror"
                           value="{{ old('locality', $client->locality) }}" required>
                    @error('locality') <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mb-6">
                <label for="address" class="block text-gray-800 text-sm font-bold mb-2 uppercase">Adresa Completa *</label>
                <textarea name="address" id="address" rows="3"
                          class="w-full border-2 rounded px-4 py-3 focus:outline-none transition font-medium
                          @error('address') border-red-600 focus:border-red-700 @else border-gray-300 focus:border-black @enderror"
                          required>{{ old('address', $client->address) }}</textarea>
                @error('address') <p class="text-red-600 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
            </div>

            <div class="mt-10 pt-6 border-t border-gray-200 flex items-center justify-between">
                <a href="{{ route('consilier.clients.index') }}" class="text-gray-500 hover:text-black font-bold uppercase tracking-wide text-sm transition">
                    Anuleaza
                </a>
                
                <button type="submit" class="bg-red-700 text-white py-4 px-10 rounded hover:bg-red-800 font-bold uppercase tracking-wider shadow-lg transition transform hover:-translate-y-0.5">
                    Salveaza Modificarile
                </button>
            </div>

        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () =>{
            const tipPersoana = document.getElementById("type");
            const afisareCNP = document.getElementById("cnp_display");
            const afisareCUI = document.getElementById("cui_display");
            const cnpInput = document.getElementById("cnp");
            const cuiInput = document.getElementById("cui");

            function updateField(){
                
                if(tipPersoana.value == "persoana_fizica"){
                    afisareCNP.classList.remove("hidden");
                    afisareCUI.classList.add("hidden");

                    cnpInput.required = true;
                    cnpInput.disabled = false;

                    cuiInput.required = false;
                    cuiInput.disabled = true;

                }
                else{ // tipPersoana.value == "persoana_juridica"
                    afisareCNP.classList.add("hidden");
                    afisareCUI.classList.remove("hidden");

                    cnpInput.required = false;
                    cnpInput.disabled = true;

                    cuiInput.required = true;
                    cuiInput.disabled = false
                }
            }
            updateField();
            tipPersoana.addEventListener("change", updateField);
        });


    </script>
</x-layout>