<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - ATP Motors CRM</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <style>body { font-family: 'Instrument Sans', sans-serif; }</style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white p-10 rounded-xl shadow-2xl w-full max-w-md border-t-4 border-red-700">
        
        <div class="text-center mb-8">
            <h1 class="text-3xl font-extrabold text-black tracking-tighter uppercase">
                <span class="text-red-700">ATP</span> MOTORS
            </h1>
            <p class="text-gray-500 text-sm uppercase tracking-widest mt-1">CRM Intern</p>
        </div>

        <h2 class="text-xl font-bold text-center mb-6 text-gray-800">Autentificare</h2>

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-700 text-red-700 p-4 rounded mb-6 text-sm font-medium">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="mb-5">
                <label class="block text-gray-700 text-sm font-bold mb-2 uppercase">Email</label>
                <input type="email" name="email" 
                       class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:border-red-700 focus:ring-0 transition text-gray-900" 
                       placeholder="nume@atp-motors.ro" required>
            </div>

            <div class="mb-8">
                <label class="block text-gray-700 text-sm font-bold mb-2 uppercase">Parola</label>
                <input type="password" name="password" 
                       class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:border-red-700 focus:ring-0 transition text-gray-900" 
                       placeholder="••••••••" required>
            </div>

            <button type="submit" class="w-full bg-red-700 text-white py-4 rounded-lg hover:bg-red-800 font-bold text-lg uppercase tracking-wider shadow-lg transition transform hover:-translate-y-0.5">
                Intra in cont
            </button>
        </form>
        
        {{-- <p class="text-center text-gray-400 text-xs mt-8">
            &copy; {{ date('Y') }} ATP Motors. Toate drepturile rezervate.
        </p> --}}
    </div>

</body>
</html>