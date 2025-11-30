<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ATP Motors CRM</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <style>
        body { font-family: 'Instrument Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 text-gray-900 min-h-screen flex flex-col">

    <nav class="bg-black text-white shadow-md">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            
            <div class="text-2xl font-bold tracking-tighter uppercase">
                <span class="text-red-600">ATP</span> MOTORS <span class="text-gray-400 text-sm font-normal normal-case ml-2">| CRM Intern</span>
            </div>

            <div class="flex items-center gap-6">
                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'text-white border-b-2 border-red-600' : 'text-gray-400 hover:text-white' }} transition font-medium pb-1">
                    Consilieri
                </a>
                
                <a href="{{ route('admin.clients.index') }}" class="{{ request()->routeIs('admin.clients.*') ? 'text-white border-b-2 border-red-600' : 'text-gray-400 hover:text-white' }} transition font-medium pb-1">
                    Clienti
                </a>

                <form method="POST" action="{{ route('logout') }}" class="ml-4">
                    @csrf
                    <button type="submit" class="bg-red-700 hover:bg-red-800 px-4 py-2 rounded text-sm font-bold transition">
                        IESIRE
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="grow container mx-auto px-6 py-8">
        {{ $slot }}
    </main>

    {{-- <footer class="bg-white border-t border-gray-200 py-4 text-center text-sm text-gray-500">
        &copy; {{ date('Y') }} ATP Motors. Toate drepturile rezervate.
    </footer> --}}

</body>
</html>