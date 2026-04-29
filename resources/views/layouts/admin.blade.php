<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/devicons/devicon@latest/devicon.min.css">
    <style>
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        .sidebar-link { transition: all 0.2s ease; }
        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(255,255,255,0.1);
            transform: translateX(4px);
        }
        .sidebar-link.active { border-left: 3px solid #60a5fa; background: rgba(96,165,250,0.1); }
        .admin-card { border: 1px solid #e2e8f0; transition: box-shadow 0.25s ease; }
        .admin-card:hover { box-shadow: 0 10px 25px -8px rgba(15,23,42,0.1); }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased">
    <!-- Mobile Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 z-30 bg-slate-900/60 backdrop-blur-sm lg:hidden hidden" onclick="toggleSidebar()"></div>

    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-slate-900 text-white flex flex-col transform -translate-x-full lg:translate-x-0 lg:static lg:z-auto transition-transform duration-300 ease-in-out">
            <div class="h-16 flex items-center gap-3 px-5 border-b border-slate-700/60 shrink-0">
                <div class="h-9 w-9 rounded-xl bg-blue-600 flex items-center justify-center text-sm font-bold">P</div>
                <div>
                    <span class="text-sm font-bold tracking-wide">Painel Admin</span>
                    <span class="block text-[0.65rem] text-slate-400 uppercase tracking-[0.2em]">Portfólio</span>
                </div>
                <button class="lg:hidden ml-auto text-slate-400 hover:text-white p-1" onclick="toggleSidebar()">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <nav class="flex-1 px-3 py-5 space-y-1 overflow-y-auto">
                <p class="text-[0.65rem] font-semibold uppercase tracking-[0.22em] text-slate-500 px-3 mb-3">Menu</p>

                <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-300 {{ request()->routeIs('admin.dashboard') ? 'active !text-blue-400' : '' }}">
                    <i class="fas fa-grid-2 w-5 text-center"></i> Dashboard
                </a>
                <a href="{{ route('admin.about.edit') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-300 {{ request()->routeIs('admin.about.*') ? 'active !text-blue-400' : '' }}">
                    <i class="fas fa-user w-5 text-center"></i> Sobre Mim
                </a>
                <a href="{{ route('admin.experiences.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-300 {{ request()->routeIs('admin.experiences.*') ? 'active !text-blue-400' : '' }}">
                    <i class="fas fa-briefcase w-5 text-center"></i> Experiências
                </a>
                <a href="{{ route('admin.projects.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-300 {{ request()->routeIs('admin.projects.*') ? 'active !text-blue-400' : '' }}">
                    <i class="fas fa-diagram-project w-5 text-center"></i> Projetos
                </a>
                <a href="{{ route('admin.technologies.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-300 {{ request()->routeIs('admin.technologies.*') ? 'active !text-blue-400' : '' }}">
                    <i class="fas fa-code w-5 text-center"></i> Tecnologias
                </a>
                <a href="{{ route('admin.social-links.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-300 {{ request()->routeIs('admin.social-links.*') ? 'active !text-blue-400' : '' }}">
                    <i class="fas fa-share-nodes w-5 text-center"></i> Redes Sociais
                </a>
            </nav>

            <div class="p-3 border-t border-slate-700/60 shrink-0">
                <a href="{{ route('portfolio.index') }}" target="_blank" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800 transition mb-2">
                    <i class="fas fa-arrow-up-right-from-square w-5 text-center"></i> Ver Portfólio
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-red-400 hover:text-red-300 hover:bg-red-500/10 transition">
                        <i class="fas fa-right-from-bracket w-5 text-center"></i> Sair
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 min-w-0 overflow-x-hidden">
            <!-- Header -->
            <header class="sticky top-0 z-20 h-16 bg-white/80 backdrop-blur-lg border-b border-slate-200/80 flex items-center justify-between px-4 sm:px-6">
                <div class="flex items-center gap-3">
                    <button class="lg:hidden p-2 -ml-2 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition" onclick="toggleSidebar()">
                        <i class="fas fa-bars text-lg"></i>
                    </button>
                    <h2 class="text-lg font-semibold text-slate-800 truncate">@yield('title', 'Dashboard')</h2>
                </div>
                <div class="flex items-center gap-2 text-sm text-slate-500">
                    <span class="hidden sm:inline">{{ auth()->user()->name ?? 'Admin' }}</span>
                    <div class="h-8 w-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                    </div>
                </div>
            </header>

            <div class="p-4 sm:p-6 lg:p-8">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-medium" role="alert">
                        <i class="fas fa-check-circle text-emerald-500"></i>
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-6 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm font-medium" role="alert">
                        <i class="fas fa-circle-exclamation text-red-500"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>
</body>
</html>
