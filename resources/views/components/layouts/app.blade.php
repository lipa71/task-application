<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management</title>

    <!-- Ta linia zastępuje niedziałające skrypty src i ładuje Tailwind v4 z Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>
<body class="bg-gray-100 py-10">

{{ $slot }}

@livewireScripts
</body>
</html>
