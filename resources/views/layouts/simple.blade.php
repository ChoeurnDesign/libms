<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Library Management System')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .btn {
            @apply px-4 py-2 rounded-md font-medium transition-colors duration-200 inline-flex items-center justify-center;
        }
        .btn-primary { @apply bg-blue-600 text-white hover:bg-blue-700; }
        .btn-success { @apply bg-green-600 text-white hover:bg-green-700; }
        .btn-danger { @apply bg-red-600 text-white hover:bg-red-700; }
    </style>
</head>
<body class="bg-gray-50">
    @yield('content')

    @if(session('success'))
        <div class="fixed top-4 right-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="fixed top-4 right-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif
</body>
</html>
