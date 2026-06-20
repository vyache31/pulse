<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pulse')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<nav class="navbar position-fixed top-0 start-50 translate-middle-x mt-3
            w-75 px-4 py-2 rounded-5
            bg-dark bg-opacity-25 border border-light border-opacity-25"
     style="backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);">

    <div class="container-fluid d-flex align-items-center">

        <a class="navbar-brand text-white fw-bold fs-2" href="/">
            Pulse
        </a>

        <div class="d-flex gap-4 mx-auto nav-links">
    <a href="#">О проекте</a>
    <a href="#">Функции</a>
    <a href="#">Как это работает</a>
</div>

        <a href="/login" class="btn btn-light btn-lg rounded-3 px-4">
            Войти
        </a>

    </div>
</nav>
<body>
<section class="hero">
</section>
</body>
</html>