<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Clima Colombia') - Clima Colombia</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <!-- Navbar fija -->
    <nav class="bg-gradient-to-r from-blue-700 to-cyan-600 text-white shadow-xl fixed top-0 w-full z-10">
        <div class="max-w-7xl mx-auto px-6 py-5 flex justify-between items-center">
            <h1 class="text-3xl font-bold tracking-wide">Clima Colombia</h1>
            <a href="{{ route('cities.create') }}" 
               class="bg-white text-blue-700 font-bold px-8 py-3 rounded-full hover:scale-105 transition shadow-lg">
                + Nueva Ciudad
            </a>
        </div>
    </nav>

    <!-- Contenido -->
    <main class="pt-28 pb-12 px-6 max-w-7xl mx-auto">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-800 p-5 rounded-lg mb-8 shadow">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-800 p-5 rounded-lg mb-8 shadow">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>