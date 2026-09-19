<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <title>Todo App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>
<body class="bg-gray-100 min-h-screen">
<div class="max-w-2xl mx-auto py-10 px-4">
    {{ $slot }}
</div>
@fluxScripts
</body>
</html>
