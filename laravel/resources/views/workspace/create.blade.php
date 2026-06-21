@extends('layouts.app')

@section('title', 'Создание Workspace')

@section('content')

<div class="container" style="max-width: 700px;">

    <div class="workspace-form-card">

        <h2 class="mb-4">
            Создать Workspace
        </h2>

        <form method="POST" action="{{ route('workspaces.store') }}">

            @csrf

            <div class="mb-3">

                <label class="form-label">
                    Название
                </label>

                <input
                    type="text"
                    name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    maxlength="30"
                    required
                >

                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <div class="d-flex gap-2">

                <button
                    type="submit"
                    class="btn btn-primary">
                    Создать
                </button>

                <a
                    href="{{ route('workspaces.index') }}"
                    class="btn btn-outline-light">
                    Отмена
                </a>

            </div>

        </form>

    </div>

</div>

@endsection
