<x-layout>
    <div class="bg-white p-8 rounded-xl shadow-md w-96">
        <h1 class="text-2xl font-bold text-center mb-6">Login</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="/login" method="POST">
            {{-- csrf because of the 419 error --}}
            @csrf

            <div class="mb-4">
                <label class="block text-black">Email</label>
                <input type="email" name="email" class="w-full border rounded px-3 py-2" placeholder="email" required>
            </div>

            <div class="mb-4">
                <label class="block text-black">Password</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2" placeholder="password" required>
            </div>

            <button type="submit" class="w-full bg-amber-600 text-white py-2 rounded hover:bg-amber-700 font-bold">
                Login
            </button>
        </form>
    </div>
</x-layout>

