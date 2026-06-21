<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Pulse')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css'])

    <style>
        body {
            background: #0f1115;
            color: #e5e7eb;
        }

        .sidebar {
            width: 280px;
            min-height: 100vh;

            background: rgba(20, 22, 28, 0.65);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);

            border-right: 1px solid rgba(255, 255, 255, 0.06);
        }

        .sidebar a {
            color: #cbd5e1;
            text-decoration: none;
        }

        .sidebar a:hover {
            color: #ffffff;
        }

        .topbar {
            background: rgba(20, 22, 28, 0.65);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);

            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .dropdown-menu {
            background: #1a1d24;
            border: 1px solid rgba(255,255,255,0.08);
        }

        .dropdown-item {
            color: #e5e7eb;
        }

        .dropdown-item:hover {
            background: rgba(255,255,255,0.06);
            color: #fff;
        }
    </style>
</head>

<body>

<div class="d-flex">

    <!-- Sidebar -->
    <div class="sidebar p-3">

        <h4 class="mb-4">
            <a href="/home" class="text-white text-decoration-none">
                Pulse
            </a>
        </h4>

        <a href="/workspaces" class="d-block mb-2">
            Workspaces
        </a>

        @foreach(auth()->user()->workspaces as $workspace)
            <a href="/workspaces/{{ $workspace->id }}" class="d-block ms-2 mb-1">
                {{ $workspace->name }}
            </a>
        @endforeach

    </div>

    <!-- Main -->
    <div class="flex-grow-1">

        <!-- Topbar -->
        <div class="topbar d-flex justify-content-end p-3">

            <div class="dropdown">
                <button class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown">
                    {{ auth()->user()->username }}
                </button>

                <ul class="dropdown-menu dropdown-menu-end">

                    <li>
                        <a class="dropdown-item" href="/profile">Профиль</a>
                    </li>

                    <li>
                        <a class="dropdown-item" href="/settings">Настройки</a>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <form method="POST" action="/logout">
                            @csrf
                            <button class="dropdown-item">Выйти</button>
                        </form>
                    </li>

                </ul>
            </div>

        </div>

        <div class="p-4">
            @yield('content')
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>