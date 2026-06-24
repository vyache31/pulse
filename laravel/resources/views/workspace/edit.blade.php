@extends('layouts.app')

@section('content')

<div class="workspace-form-card" style="max-width: 800px; margin: 40px auto;">

    <h3 class="mb-4 text-white">
        Настройки workspace
    </h3>

    <form method="POST" action="{{ route('workspaces.update', $workspace) }}">
        @csrf
        @method('PATCH')

        <div class="mb-3">
            <label class="form-label text-white">Название</label>

            <input
                type="text"
                name="name"
                class="form-control"
                value="{{ old('title', $workspace->title) }}"
                required
            >
        </div>

        <button class="btn btn-primary">
            Сохранить
        </button>
    </form>

    <hr class="my-4" style="border-color: rgba(255,255,255,0.1)">

    <h5 class="text-white mb-3">Участники</h5>

    <div class="mb-3">
        @foreach($workspace->members as $workspace_member)
            <div class="d-flex justify-content-between align-items-center p-2 mb-2"
                 style="background: rgba(255,255,255,0.04); border-radius: 10px;">

                <div>
                    <div class="text-white">
                        {{ $workspace_member->user->username }}
                    </div>

                    <small class="text-muted">
                        {{ $workspace_member->role }}
                    </small>
                </div>

                <form method="POST"
                      action="{{ route('workspaces.workspace-members.destroy', [$workspace, $workspace_member]) }}">
                    @csrf
                    @method('DELETE')

                    <button class="btn btn-sm btn-outline-danger">
                        удалить
                    </button>
                </form>

            </div>
        @endforeach
    </div>

    <h5 class="text-white mb-2">
        Добавить участника
    </h5>

    <input
        type="text"
        id="userSearch"
        class="form-control mb-2"
        placeholder="Введите ник..."
    >

    <div id="searchResults"></div>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('userSearch');
    const resultsContainer = document.getElementById('searchResults');
    let timer = null;
    let currentRequest = null;

    searchInput.addEventListener('input', function () {
        clearTimeout(timer);
        const q = this.value.trim();

        if (q.length < 2) {
            resultsContainer.innerHTML = '';
            return;
        }

        resultsContainer.innerHTML = '<div class="text-muted">Поиск...</div>';

        timer = setTimeout(async () => {
            try {
                if (currentRequest) {
                    currentRequest.abort();
                }
                currentRequest = new AbortController();

                const res = await fetch(
                    `/users/search?q=${encodeURIComponent(q)}&workspace_id={{ $workspace->id }}`,
                    { signal: currentRequest.signal }
                );

                if (!res.ok) throw new Error('Ошибка сети');

                const users = await res.json();

                if (users.length === 0) {
                    resultsContainer.innerHTML = '<div class="text-muted">Пользователи не найдены</div>';
                    return;
                }

                const html = users.map(u => `
                    <div class="d-flex justify-content-between align-items-center p-2 mb-1"
                         style="background: rgba(255,255,255,0.04); border-radius: 10px;">
                        <div class="text-white">${u.username}</div>
                        <form method="POST"
                              action="{{ route('workspaces.workspace-members.store', $workspace) }}">
                            @csrf
                            <input type="hidden" name="user_id" value="${u.id}">
                            <input type="hidden" name="role" value="member">
                            <button class="btn btn-sm btn-primary">добавить</button>
                        </form>
                    </div>
                `).join('');

                resultsContainer.innerHTML = html;
            } catch (error) {
                if (error.name === 'AbortError') return;
                console.error(error);
                resultsContainer.innerHTML = '<div class="text-danger">Ошибка загрузки</div>';
            }
        }, 300);
    });
});
</script>
@endpush