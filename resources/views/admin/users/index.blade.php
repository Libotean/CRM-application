<x-layout>
    <div class="container mx-auto p-4">
        <a href="/" class="inline-block bg-blue-700 text-white py-2 px-6 rounded-lg hover:bg-blue-800 font-bold transition duration-150 mb-4">
            Back
        </a>

        <h1 class="text-3xl font-bold text-white mb-6">
            Gestionare Consilieri
        </h1>

        <a href="{{ route('admin.users.create') }}" class="inline-block bg-blue-700 text-white py-2 px-6 rounded-lg hover:bg-blue-800 font-bold transition duration-150 mb-4">
            + Adauga Consilier Nou
        </a>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-200">
                <tr>
                    <th class="py-3 px-4 text-left">Nume Complet</th>
                    <th class="py-3 px-4 text-left">Email</th>
                    <th class="py-3 px-4 text-left">Rol</th>
                    <th class="py-3 px-4 text-left">Status</th>
                    <th class="py-3 px-4 text-left">Acțiuni</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3 px-4">{{ $user->lastname }} {{ $user->firstname }}</td>
                        <td class="py-3 px-4">{{ $user->email }}</td>
                        <td class="py-3 px-4 capitalize">{{ $user->role }}</td>
                        <td class="py-3 px-4">
                            @if ($user->is_active)
                                <span class="text-green-600 font-bold">Activ</span>
                            @else
                                <span class="text-red-600 font-bold">Inactiv</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <a href="#" class="inline-block bg-blue-700 text-white py-1 px-4 rounded-lg hover:bg-blue-800 font-bold transition duration-150 text-sm">
                                Detalii
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layout>
