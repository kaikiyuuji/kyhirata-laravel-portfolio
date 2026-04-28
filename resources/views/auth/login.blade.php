<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrativo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center">
    <div class="bg-gray-800 p-8 rounded-lg shadow-lg w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center">Acesso Restrito</h1>
        
        @if ($errors->any())
            <div class="bg-red-500 text-white p-3 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">E-mail</label>
                <input type="email" name="email" required class="w-full bg-gray-700 border border-gray-600 rounded p-2 text-white focus:ring focus:ring-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Senha</label>
                <input type="password" name="password" required class="w-full bg-gray-700 border border-gray-600 rounded p-2 text-white focus:ring focus:ring-blue-500 outline-none">
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 font-bold py-2 px-4 rounded transition-colors">
                Entrar no Painel
            </button>
        </form>
    </div>
</body>
</html>
