<x-layout>
    @if ( $user->role == 'admin' )

        <h1 class="text-3xl font-bold text-white">
            Hello, Admin!
        </h1>
        <p class="text-white"> You have full access. </p>

    @else

        <h1 class="text-3xl font-bold text-white">
            Hello, {{ $user->lastname }} {{ $user->firstname }}!
        </h1>
        <p class="text-white"> Role - {{ $user->role }} </p>

    @endif

    <form method="POST" action="{{ route('logout') }}" class="mt-6">
        @csrf
        <button type="submit" class="bg-amber-600 text-white py-2 px-6 rounded-lg hover:bg-amber-700 font-bold transition duration-150">
            Logout
        </button>
    </form>
</x-layout>
