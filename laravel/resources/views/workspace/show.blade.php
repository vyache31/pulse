@extends('layouts.app')

@section('content')

<div class="kanban-wrapper">

    <div class="kanban-board">

        @foreach($workspace->columns as $column)

            <div class="kanban-column" data-column-id="{{ $column->id }}">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <h5 class="mb-0 text-white">
                        {{ $column->title }}
                    </h5>

                    <a
                        href="{{ route('workspaces.columns.edit', [$workspace, $column]) }}"
                        class="btn btn-sm btn-outline-light"
                    >
                        ⚙
                    </a>

                </div>

                <div class="tasks-container">

    				@foreach($column->tasks as $task)

        				<div class="task-card" data-task-id="{{ $task->id }}">

            				<div class="d-flex justify-content-between align-items-start">

                				<div style="width: 100%;">

                    				<div class="fw-bold text-white">
                        				{{ $task->title }}
                    				</div>

                    				@if($task->description)
                        				<small class="text-muted d-block">
                            				{{ $task->description }}
                        				</small>
                    				@endif

                    				@if(is_array($task->tags))
                        				<div class="mt-2">
                           					@foreach($task->tags as $tag)
                                				<span class="badge bg-secondary">
                                    				{{ $tag }}
                                				</span>
                            				@endforeach
                        				</div>
                   				 	@endif

                    				@if($task->doer)
                        				<div class="text-info mt-1">
                            				👤 {{ $task->doer->username }}
                        				</div>
                    				@endif

                				</div>

                				<a
                    				href="{{ route('workspaces.columns.tasks.edit', [$workspace, $column, $task]) }}"
                    				class="btn btn-sm btn-link text-light p-0"
                				>
                    				⚙
                				</a>

            				</div>

        				</div>

    				@endforeach

				</div>

                <a
                    href="{{ route('workspaces.columns.tasks.create', [$workspace, $column]) }}"
                    class="btn btn-outline-light w-100 mt-2"
                >
                    + Задача
                </a>

            </div>

        @endforeach

        <a
            href="{{ route('workspaces.columns.create', $workspace) }}"
            class="kanban-column text-center text-white d-flex align-items-center justify-content-center"
            style="opacity: 0.6;"
        >
            + Добавить колонку
        </a>

    </div>

</div>
<script>

const workspaceId = {{ $workspace->id }};
const ws = new WebSocket('wss://api.pulse.vyache.space/ws');

ws.onopen = () => console.log('WebSocket connected');
ws.onerror = (err) => console.error(err);
ws.onclose = () => console.log('WebSocket disconnected');

function buildTaskHtml(task) {
    const tagsHtml = (Array.isArray(task.tags) && task.tags.length)
        ? `<div class="mt-2">${task.tags.map(tag => `<span class="badge bg-secondary">${tag}</span>`).join('')}</div>`
        : '';
    const doerHtml = task.assigned_to
        ? `<div class="text-info mt-1">👤 ${task.assigned_to}</div>`
        : '';
    const editUrl = `/workspaces/${workspaceId}/columns/${task.column_id}/tasks/${task.id}/edit`;
    return `
        <div class="task-card" data-task-id="${task.id}">
            <div class="d-flex justify-content-between align-items-start">
                <div style="width:100%">
                    <div class="fw-bold text-white">${task.title}</div>
                    ${task.description ? `<small class="text-muted d-block">${task.description}</small>` : ''}
                    ${tagsHtml}
                    ${doerHtml}
                </div>
                <a href="${editUrl}" class="btn btn-sm btn-link text-light p-0">⚙</a>
            </div>
        </div>
    `;
}

function findTaskCard(taskId) {
    return document.querySelector(`.task-card[data-task-id="${taskId}"]`);
}

function getColumnContainer(columnId) {
    return document.querySelector(`[data-column-id="${columnId}"] .tasks-container`);
}

ws.onmessage = (event) => {
    const msg = JSON.parse(event.data);
    console.log('WS message:', msg);

    const task = msg.post;
    if (!task) return;

    if (task.workspace_id && task.workspace_id != workspaceId) return;

    switch (msg.type) {
        case 'new_task': {
            const column = getColumnContainer(task.column_id);
            if (column) {
                column.insertAdjacentHTML('beforeend', buildTaskHtml(task));
            }
            break;
        }

        case 'updated_task': {
            const oldCard = findTaskCard(task.id);
            if (oldCard) {
                oldCard.remove();
                const newColumn = getColumnContainer(task.column_id);
                if (newColumn) {
                    newColumn.insertAdjacentHTML('beforeend', buildTaskHtml(task));
                } else {й
                    oldCard.outerHTML = buildTaskHtml(task);
                }
            } else {
                console.warn('Task card not found for update:', task.id);
            }
            break;
        }

        case 'deleted_task': {
            const card = findTaskCard(task.id);
            if (card) {
                card.remove();
            }
            break;
        }

        default:
            console.warn('Unknown message type:', msg.type);
    }
};
</script>
@endsection