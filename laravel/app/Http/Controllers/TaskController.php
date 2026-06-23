<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Column;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Workspace $workspace, Column $column)
    {
        $this->authorize('create', $column);

		return view('task.create', [
			'workspace' => $workspace,
			'column' => $column
		]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Workspace $workspace, Column $column)
    {
		$this->authorize('create', $column);
		
        $data = $request->validate([
			'title' => ['required', 'string', 'max:30'],
			'description' => ['nullable', 'string', 'max:255'],
			'tags' => ['nullable', 'string', 'max:255'],
			'assigned_to' => ['nullable', 'integer'],
		]);

		$tags = collect(explode(',', $request->tags))
			->map(fn ($tag) => trim($tag))
			->filter()
			->values()
			->all();

		$task = $column->tasks()->create([
			'title' => $data['title'],
			'description' => $data['description'],
			'tags' => $tags ?? [],
			'assigned_to' => $data['assigned_to'] ?? null,
		]);

		Redis::connection()->executeRaw([
			'PUBLISH',
			'new_task',
			json_encode([
			'workspace_id' => $workspace->id,
			'column_id' => $column->id,
			'id' => $task->id,
			'title' => $task->title,
			'description' => $task->description,
			'tags' => $task->tags,
			'assigned_to' => $task->doer->username,
			])
		]);

		return redirect()->route('workspaces.show', [
			'workspace' => $workspace
		]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);

		return view('task.show', [
			'task' => $task
		]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Workspace $workspace, Column $column, Task $task)
    {
        $this->authorize('update', $workspace);

		return view('task.edit', [
			'workspace' => $workspace, 
			'column' => $column,
			'task' => $task
		]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Workspace $workspace, Column $column, Task $task)
    {
        $this->authorize('update', $workspace);
		$data = $request->validate([
			'title' => ['required', 'string', 'max:30'],
			'description' => ['nullable', 'string', 'max:255'],
			'tags' => ['nullable', 'string', 'max:255'],
			'assigned_to' => ['nullable', 'integer'],
		]);

		$tags = collect(explode(',', $request->tags))
			->map(fn ($tag) => trim($tag))
			->filter()
			->values()
			->all();

		$task->update([
			'title' => $data['title'],
			'description' => $data['description'],
			'tags' => $tags,
			'assigned_to' => $data['assigned_to'] ?? null,	
		]);

		Redis::connection()->executeRaw([
			'PUBLISH',
			'updated_task',
			json_encode([
			'workspace_id' => $workspace->id,
			'column_id' => $column->id,
			'id' => $task->id,
			'title' => $task->title,
			'description' => $task->description,
			'tags' => $task->tags,
			'assigned_to' => $task->doer->username,
			])
		]);


		return redirect()->route('workspaces.show', [
			'workspace' => $workspace
		]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Workspace $workspace, Column $column, Task $task)
    {
        $this->authorize('delete', $workspace);
        $task->delete();

		Redis::connection()->executeRaw([
			'PUBLISH',
			'deleted_task',
			json_encode([
			'workspace_id' => $workspace->id,
			'column_id' => $column->id,
			'id' => $task->id,
			])
		]);


		return redirect()->route('workspaces.show', [
			'workspace' => $workspace
		]);

    }
}
