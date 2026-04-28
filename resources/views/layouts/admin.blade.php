<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Portfólio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/devicon.min.css">
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-900">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white flex flex-col">
            <div class="h-16 flex items-center justify-center border-b border-gray-700">
                <span class="text-xl font-bold uppercase tracking-wider">Painel Admin</span>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 rounded hover:bg-gray-700 transition"><i class="fas fa-home w-6"></i> Dashboard</a>
                <a href="{{ route('admin.about.edit') }}" class="block px-4 py-2 rounded hover:bg-gray-700 transition"><i class="fas fa-user w-6"></i> Sobre Mim</a>
                <a href="{{ route('admin.experiences.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700 transition"><i class="fas fa-briefcase w-6"></i> Experiências</a>
                <a href="{{ route('admin.projects.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700 transition"><i class="fas fa-project-diagram w-6"></i> Projetos</a>
                <a href="{{ route('admin.technologies.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700 transition"><i class="fas fa-code w-6"></i> Tecnologias</a>
                <a href="{{ route('admin.social-links.index') }}" class="block px-4 py-2 rounded hover:bg-gray-700 transition"><i class="fas fa-link w-6"></i> Redes Sociais</a>
            </nav>
            <div class="p-4 border-t border-gray-700">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded transition">
                        <i class="fas fa-sign-out-alt"></i> Sair
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <!-- Header -->
            <header class="h-16 bg-white shadow flex items-center px-6">
                <h2 class="text-xl font-semibold text-gray-800">@yield('title', 'Dashboard')</h2>
            </header>

            <div class="p-6">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Content -->
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
