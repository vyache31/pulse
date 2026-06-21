@extends('layouts.app')

@section('content')

<div class="kanban-board">

    @foreach($workspace->columns as $column)

        <div class="kanban-column">

            <h5 class="mb-3">{{ $column->title }}</h5>

            @foreach($column->tasks as $task)

                <div class="task-card">
                    {{ $task->title }}
                </div>

            @endforeach

            <button
                class="btn btn-outline-light w-100 mt-2"
                data-bs-toggle="modal"
                data-bs-target="#createTaskModal"
                data-column="{{ $column->id }}"
            >
                + Задача
            </button>

        </div>

    @endforeach

    <button
        class="add-column-card"
        data-bs-toggle="modal"
        data-bs-target="#createColumnModal"
    >
        + Добавить колонку
    </button>

</div>

<!-- Модалка создания задачи -->
<div
    class="modal fade"
    id="createTaskModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <div class="modal-content bg-dark text-white">

            <form method="POST" action="{{ route('workspaces.tasks.store', $workspace) }}">

                @csrf

                <input
                    type="hidden"
                    name="column_id"
                    id="column_id"
                >

                <div class="modal-header">

                    <h5 class="modal-title">
                        Создать задачу
                    </h5>

                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <input
                        type="text"
                        name="title"
                        class="form-control"
                        placeholder="Название задачи"
                        required
                    >

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Отмена
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary">
                        Создать
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- Модалка создания колонки -->
<div
    class="modal fade"
    id="createColumnModal"
    tabindex="-1"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <div class="modal-content bg-dark text-white">

            <form method="POST" action="{{ route('workspaces.columns.store', $workspace) }}">

                @csrf

                <input
                    type="hidden"
                    name="workspace_id"
                    value="{{ $workspace->id }}"
                >

                <div class="modal-header">

                    <h5 class="modal-title">
                        Создать колонку
                    </h5>

                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <input
                        type="text"
                        name="title"
                        class="form-control"
                        placeholder="Название колонки"
                        required
                    >

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Отмена
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary">
                        Создать
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script>
document
    .getElementById('createTaskModal')
    .addEventListener('show.bs.modal', function (event) {

        let button = event.relatedTarget;

        document.getElementById('column_id').value =
            button.dataset.column;
    });
</script>

@endsection