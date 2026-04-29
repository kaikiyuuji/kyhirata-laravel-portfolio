<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Administração</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background:
                radial-gradient(circle at 20% 20%, rgba(99,102,241,0.12), transparent 40%),
                radial-gradient(circle at 80% 80%, rgba(59,130,246,0.1), transparent 40%),
                linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        }
        .login-card {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(148, 163, 184, 0.15);
            backdrop-filter: blur(24px);
        }
        .input-field {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(148, 163, 184, 0.2);
            transition: border-color 0.25s ease, box-shadow 0.25s ease;
        }
        .input-field:focus {
            border-color: rgba(96, 165, 250, 0.6);
            box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.15);
            outline: none;
        }
        .login-btn {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .login-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 24px -6px rgba(59, 130, 246, 0.5);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4 text-white">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-600 text-xl font-bold shadow-lg shadow-blue-600/30 mb-4">
                P
            </div>
            <h1 class="text-2xl font-bold tracking-tight">Acesso Restrito</h1>
            <p class="text-sm text-slate-400 mt-1">Faça login para acessar o painel administrativo</p>
        </div>

        <!-- Card -->
        <div class="login-card rounded-2xl p-6 sm:p-8 shadow-2xl">
            @if ($errors->any())
                <div class="flex items-center gap-3 bg-red-500/15 border border-red-500/30 text-red-300 px-4 py-3 rounded-xl text-sm mb-6">
                    <i class="fas fa-circle-exclamation"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">E-mail</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">
                            <i class="fas fa-envelope text-sm"></i>
                        </span>
                        <input type="email" name="email" required value="{{ old('email') }}"
                            class="input-field w-full rounded-xl pl-10 pr-4 py-3 text-sm text-white placeholder-slate-500"
                            placeholder="admin@example.com">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Senha</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">
                            <i class="fas fa-lock text-sm"></i>
                        </span>
                        <input type="password" name="password" required
                            class="input-field w-full rounded-xl pl-10 pr-4 py-3 text-sm text-white placeholder-slate-500"
                            placeholder="••••••••">
                    </div>
                </div>
                <button type="submit" class="login-btn w-full font-semibold py-3 px-4 rounded-xl text-sm text-white shadow-lg">
                    <i class="fas fa-right-to-bracket mr-2"></i>
                    Entrar no Painel
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-slate-500 mt-6">
            <a href="{{ url('/') }}" class="hover:text-slate-300 transition">&larr; Voltar ao portfólio</a>
        </p>
    </div>
</body>
</html>
