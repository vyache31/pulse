@extends('layouts.app')

@section('content')

<div class="kanban-board">

    @foreach($workspace->columns as $column)

        <div class="kanban-column">

            <div class="d-flex justify-content-between align-items-center mb-3">
    			<h5 class="mb-0">{{ $column->title }}</h5>

    			<button
        			class="btn btn-sm"
        			data-bs-toggle="modal"
        			data-bs-target="#editColumnModal{{ $column->id }}"
    			>
        			⚙
    			</button>
			</div>

            @foreach($column->tasks as $task)

                <div class="task-card">

    				<div class="d-flex justify-content-between">

        			<span>{{ $task->title }}</span>

        			<button
            			class="btn btn-sm btn-link text-light p-0"
            			data-bs-toggle="modal"
            			data-bs-target="#editTaskModal{{ $task->id }}"
        			>
            			⚙
        			</button>

    				</div>

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

		<div
    		class="modal fade"
    		id="editColumnModal{{ $column->id }}"
    		tabindex="-1"
		>

    		<div class="modal-dialog">

        		<div class="modal-content bg-dark text-white">

            		<form
                		method="POST"
                		action="{{ route('workspaces.columns.update', [$workspace, $column]) }}"
            		>
                		@csrf
                		@method('PATCH')

                		<div class="modal-header">
                    		<h5>Изменить колонку</h5>
                		</div>

                		<div class="modal-body">

                    		<input
                        		type="text"
                        		name="title"
                        		class="form-control"
                        		value="{{ $column->title }}"
                        		required
                    		>

                		</div>

                		<div class="modal-footer">

                    		<button
                        	type="submit"
                        	class="btn btn-primary"
                    		>
                        		Сохранить
                    		</button>

                		</div>

            		</form>

        		</div>

    		</div>

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

            <form method="POST" action="{{ route('tasks.store') }}">

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

					<div class="mb-3">

    				<label class="form-label">Описание</label>

    					<textarea
        					name="description"
        					class="form-control"
       						rows="3"
    					></textarea>

					</div>

					<div class="mb-3">

    					<label class="form-label">Теги</label>

    						<input
        						type="text"
        						name="tags"
        						class="form-control"
        						placeholder="bug, backend, urgent"
    						>

					</div>

					<div class="mb-3">

    					<label class="form-label">
       						Исполнитель
    					</label>

    					<select
        					name="assignee_id"
        					class="form-select"
    					>

        					<option value="">
            						Не назначен
        					</option>

        					@foreach($workspace->users as $user)

            					<option value="{{ $user->id }}">
                					{{ $user->username }}
            					</option>

        					@endforeach

    					</select>

					</div>

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