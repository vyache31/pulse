@extends('workspace.show')

@section('modal')

<div class="modal-backdrop-custom">

<div class="card bg-dark text-white p-4">

    <h3 class="mb-4">Изменить колонку</h3>

    <form
        method="POST"
        action="{{ route('workspaces.columns.update', [$workspace, $column]) }}"
    >
        @csrf
        @method('PATCH')

        <input
            type="text"
            name="title"
            class="form-control mb-3"
            value="{{ old('title', $column->title) }}"
            required
        >

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
        action="{{ route('workspaces.columns.destroy', [$workspace, $column]) }}"
        onsubmit="return confirm('Удалить колонку? Все задачи внутри будут потеряны.')"
    >
        @csrf
        @method('DELETE')

        <button
            type="submit"
            class="btn btn-danger w-100"
        >
            Удалить колонку
        </button>

    </form>

</div>

</div>

@endsection
