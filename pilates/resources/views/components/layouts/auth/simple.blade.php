<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Sistem Informasi Desa') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; }

        .gov-pattern {
            background-color: #eff6ff;
            background-image:
                radial-gradient(circle at 20% 20%, rgba(59,130,246,0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(37,99,235,0.06) 0%, transparent 50%),
                url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%233b82f6' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .dark .gov-pattern {
            background-color: #0f172a;
            background-image:
                radial-gradient(circle at 20% 20%, rgba(59,130,246,0.12) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(37,99,235,0.08) 0%, transparent 50%);
        }

        .login-card {
            animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .logo-ring {
            animation: fadeIn 0.5s ease both;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to   { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body class="h-full gov-pattern dark:gov-pattern">

    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-10">
        {{ $slot }}
    </div>

    @livewireScripts
</body>
</html>
