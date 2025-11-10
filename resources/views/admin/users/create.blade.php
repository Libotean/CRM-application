<x-layout>
    <div class="container mx-auto p-4">
        <a href="{{ route('admin.users.index') }}" class="inline-block bg-blue-700 text-white py-2 px-6 rounded-lg hover:bg-blue-800 font-bold transition duration-150 mb-4">
            ← Inapoi
        </a>

        <h1 class="text-3xl font-bold text-white mb-6">
            Adauga Consilier Nou
        </h1>

        <form action="{{ route('admin.users.store') }}" method="POST" class="bg-white p-8 rounded-lg shadow-lg">
            @csrf

            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6">
                    <p class="font-bold">Va rugam corectati erorile de mai jos.</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="lastname" class="block text-gray-700 font-bold mb-2">Nume *</label>
                    <input type="text" name="lastname" id="lastname"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 @error('lastname') border-red-500 @enderror"
                           value="{{ old('lastname') }}" required>
                    @error('lastname') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="firstname" class="block text-gray-700 font-bold mb-2">Prenume *</label>
                    <input type="text" name="firstname" id="firstname"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 @error('firstname') border-red-500 @enderror"
                           value="{{ old('firstname') }}" required>
                    @error('firstname') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-gray-700 font-bold mb-2">Email *</label>
                    <input type="email" name="email" id="email"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 @error('email') border-red-500 @enderror"
                           value="{{ old('email') }}" required>
                    @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="block text-gray-700 font-bold mb-2">Parolă *</label>
                    <input type="password" name="password" id="password"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 @error('password') border-red-500 @enderror"
                           required>
                    @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="role" class="block text-gray-700 font-bold mb-2">Rol *</label>
                    <select name="role" id="role"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 @error('role') border-red-500 @enderror"
                            required>
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Consilier</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                    @error('role') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="country" class="block text-gray-700 font-bold mb-2">Țara *</label>
                    <input type="text" name="country" id="country"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 @error('country') border-red-500 @enderror"
                           value="{{ old('country', 'România') }}" required>
                    @error('country') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="county" class="block text-gray-700 font-bold mb-2">Județ *</label>
                    <input type="text" name="county" id="county"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 @error('county') border-red-500 @enderror"
                           value="{{ old('county') }}" required>
                    @error('county') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="locality" class="block text-gray-700 font-bold mb-2">Localitate *</label>
                    <input type="text" name="locality" id="locality"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 @error('locality') border-red-500 @enderror"
                           value="{{ old('locality') }}" required>
                    @error('locality') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="date_start" class="block text-gray-700 font-bold mb-2">Data Început</label>
                    <input type="date" name="date_start" id="date_start"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 @error('date_start') border-red-500 @enderror"
                           value="{{ old('date_start') }}">
                    @error('date_start') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="date_end" class="block text-gray-700 font-bold mb-2">Data Sfârșit</label>
                    <input type="date" name="date_end" id="date_end"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 @error('date_end') border-red-500 @enderror"
                           value="{{ old('date_end') }}">
                    @error('date_end') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-8 flex items-center gap-4">
                <button type="submit" class="bg-blue-700 text-white py-3 px-8 rounded-lg hover:bg-blue-800 font-bold transition duration-150">
                    Creeaza Cont
                </button>
                <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-800 font-bold transition duration-150">
                    Anuleaza
                </a>
            </div>
        </form>
    </div>
</x-layout>
