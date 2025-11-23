<x-layout>
    <div class="container mx-auto">
        
        <div class="flex justify-between items-center mb-8 border-b-2 border-black pb-4">
            <div>
                <h1 class="text-3xl font-extrabold text-black uppercase tracking-tight">
                    Gestionare Consilieri
                </h1>
                <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-black font-bold text-sm flex items-center mb-2 transition">
                    <span class="mr-1">&larr;</span> Inapoi
                </a>
            
            </div>
            <a href="{{ route('admin.users.create') }}" class="bg-red-700 text-white px-6 py-3 rounded hover:bg-red-800 transition font-bold shadow-sm flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                ADAUGA NOU
            </a>
        </div>

        @if (session('success'))
            <div id="flash-message" class="bg-green-100 border-l-4 border-green-600 text-green-800 p-4 rounded shadow-sm mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-4 px-6 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nume Complet</th>
                        <th class="py-4 px-6 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="py-4 px-6 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Rol</th>
                        <th class="py-4 px-6 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status Cont</th>
                        <th class="py-4 px-6 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actiuni</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($users as $user)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="py-4 px-6 whitespace-nowrap">
                            <div class="font-bold text-gray-900">{{ $user->full_name }}</div>
                        </td>
                        
                        <td class="py-4 px-6 whitespace-nowrap text-gray-600">
                            {{ $user->email }}
                        </td>

                        <td class="py-4 px-6 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-bold rounded uppercase {{ $user->role == 'admin' ? 'bg-black text-white' : 'bg-gray-200 text-gray-800' }}">
                                {{ $user->role }}
                            </span>
                        </td>

                        <td class="py-4 px-6 whitespace-nowrap">
                            @if ($user->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5"></span> Activ
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <span class="w-2 h-2 bg-red-500 rounded-full mr-1.5"></span> Inactiv
                                </span>
                            @endif
                        </td>

                        <td class="py-4 px-6 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('admin.users.show', $user->id) }}" class="text-red-700 hover:text-red-900 font-bold mr-3 transition">
                                Detalii
                            </a>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="text-gray-400 hover:text-gray-600 transition">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            
            @if($users->isEmpty())
                <div class="p-8 text-center text-gray-500">
                    Nu exista niciun utilizator inregistrat.
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const flashMessage = document.getElementById('flash-message');
            if(flashMessage) {
                setTimeout(()=> {
                    flashMessage.style.transition = 'opacity 0.5s ease-out';
                    flashMessage.style.opacity = '0';
                    setTimeout(() => { flashMessage.style.display = 'none'; }, 500);
                }, 4000);
            }
        });
    </script>
</x-layout>