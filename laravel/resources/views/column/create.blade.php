@extends('workspace.show')

@section('modal')

<div class="modal-backdrop-custom">

    <div class="card bg-dark text-white p-4">

        <h3>Создать колонку</h3>

        <form
            method="POST"
            action="{{ route('workspaces.columns.store', $workspace) }}"
        >
            @csrf

            <input
                type="text"
                name="title"
                class="form-control mb-3"
            >

            <button
                class="btn btn-primary"
            >
                Создать
            </button>

        </form>

    </div>

</div>

@endsection
