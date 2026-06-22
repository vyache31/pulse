@extends('workspace.show')

@section('modal')

<div class="modal-backdrop-custom">

    <div class="card bg-dark text-white p-4" style="width: 600px;">

        <h3 class="mb-4">Изменить задачу</h3>

        <form
            method="POST"
            action="{{ route('workspaces.columns.tasks.update', [$workspace, $column, $task]) }}"
        >
            @csrf
            @method('PATCH')

            <div class="mb-3">

                <label class="form-label">
                    Название
                </label>

                <input
                    type="text"
                    name="title"
                    class="form-control"
                    value="{{ old('title', $task->title) }}"
                    required
                >

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Описание
                </label>

                <textarea
                    name="description"
                    rows="4"
                    class="form-control"
                >{{ old('description', $task->description) }}</textarea>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Теги
                </label>

                <input
                    type="text"
                    name="tags"
                    class="form-control"
                    value="{{ old('tags', implode(', ', $task->tags ?? [])) }}"
                    placeholder="backend, bug, urgent"
                >

                <small class="text-secondary">
                    Через запятую
                </small>

            </div>

            <div class="mb-4">

                <label class="form-label">
                    Исполнитель
                </label>

                <select
                    name="assigned_to"
                    class="form-select"
                >

                    <option value="">
                        Не назначен
                    </option>

                    @foreach($workspace->users as $user)

                        <option
                            value="{{ $user->id }}"
                            @selected(old('assigned_to', $task->assigned_to) == $user->id)
                        >
                            {{ $user->username }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div class="d-flex gap-2">

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Сохранить
                </button>

                <a
                    href="{{ route('workspaces.show', $workspace) }}"
                    class="btn btn-secondary"
                >
                    Отмена
                </a>

            </div>

        </form>

        <hr class="my-4">

        <form
            method="POST"
            action="{{ route('workspaces.columns.tasks.destroy', [$workspace, $column, $task]) }}"
            onsubmit="return confirm('Удалить задачу?')"
        >
            @csrf
            @method('DELETE')

            <button
                type="submit"
                class="btn btn-danger w-100"
            >
                Удалить задачу
            </button>

        </form>

    </div>

</div>

@endsection