<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Listado de usuarios</title> <!--   - Styde.net</title> -->
</head>
<body>
    <h1>{{ $title }}</h1>

    <hr>

    <ul>
        {{-- @unless(empty($users))  sino inverso @if --}}
        @forelse ($users as $user)
            <li>{{ $user->name }}</li>
        @empty
            <li>No hay usuarios registrados.</li>
        @endforelse
    </ul>
</body>
</html>
