@extends('workspace.show')

@section('modal')

<div class="modal-backdrop-custom">

    <div class="card bg-dark text-white p-4" style="width: 600px;">

        <h3 class="mb-4">Создать задачу</h3>

        <form
            method="POST"
            action="{{ route('workspaces.columns.tasks.store', [$workspace, $column]) }}"
        >
            @csrf

            <div class="mb-3">

                <label class="form-label">
                    Название
                </label>

                <input
                    type="text"
                    name="title"
                    class="form-control"
                    value="{{ old('title') }}"
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
                >{{ old('description') }}</textarea>

            </div>

            <div class="mb-3">

                <label class="form-label">
                    Теги
                </label>

                <input
                    type="text"
                    name="tags"
                    class="form-control"
                    value="{{ old('tags') }}"
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
                            @selected(old('assigned_to') == $user->id)
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
                    Создать
                </button>

                <a
                    href="{{ route('workspaces.show', $workspace) }}"
                    class="btn btn-secondary"
                >
                    Отмена
                </a>

            </div>

        </form>

    </div>

</div>

@endsection