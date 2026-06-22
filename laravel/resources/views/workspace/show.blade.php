@extends('layouts.app')

@section('content')

<div class="kanban-wrapper">

    <div class="kanban-board">

        @foreach($workspace->columns as $column)

            <div class="kanban-column">

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

                @foreach($column->tasks as $task)

                    <div class="task-card">

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

@endsection