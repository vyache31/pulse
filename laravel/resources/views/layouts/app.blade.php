<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Pulse')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>

<body>
	
	<div class="d-flex">

    	<div class="sidebar p-3">

        	<div class="sidebar-section">

    			<div class="d-flex justify-content-between align-items-center">
        			<span>Workspaces</span>

        				<a href="{{ route('workspaces.create') }}"
           					class="workspace-add-btn">
           					+
        				</a>
    			</div>

    		<div class="workspace-list mt-2">
        		@foreach(auth()->user()->workspaces as $workspace)
            		<div class="workspace-header d-flex align-items-center justify-content-between px-3 mb-3">

    					<a
							href="{{ route('workspaces.show', $workspace) }}"
							class="text-white mb-0">
        					{{ $workspace->name }}
    					</a>

    					<a
        					href="{{ route('workspaces.edit', $workspace) }}"
        					class="workspace-settings-btn"
        					title="Настройки workspace"
    					>
       						 ⚙
    					</a>

					</div>
        		@endforeach
    		</div>

		</div>
    </div>

    <div class="flex-grow-1">

        <div class="d-flex justify-content-end p-3">

            <div class="dropdown">
                <button class="user-dropdown-btn dropdown-toggle"
                        data-bs-toggle="dropdown">
                    {{ auth()->user()->username }}
                </button>

                <ul class="dropdown-menu dropdown-menu-end mt-2">

                    <li><a class="dropdown-item" href="/profile">Профиль</a></li>
                    <li><a class="dropdown-item" href="/settings">Настройки</a></li>

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
			@yield('modal')
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>