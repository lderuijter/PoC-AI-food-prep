<!DOCTYPE html>
<html lang="nl" class="dark">
<head>
    <meta charset="UTF-8">
    <title>Ingrediënten</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-gray-100 min-h-screen">
<div class="max-w-2xl mx-auto py-10 px-4">
    {{ $slot }}
</div>
</body>
</html>
