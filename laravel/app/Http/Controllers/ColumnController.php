<?php

namespace App\Http\Controllers;

use App\Models\Column;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ColumnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
		//
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Workspace $workspace)
    {
        $this->authorize('create', $workspace);

		return view('column.create', [
			'workspace' => $workspace
		]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Workspace $workspace)
    {
		$this->authorize('create', $workspace);
        $data = $request->validate([
			'title' => ['required', 'string', 'max:30'],
		]);

		$lastColumnIndex = $workspace->columns()
			->max('position');

		$column = $workspace->columns()->create([
			'title' => $data['title'],
			'position' => $lastColumnIndex + 1,
		]);

		Redis::connection()->executeRaw([
			'PUBLISH',
			'new_column',
			json_encode([
			'workspace_id' => $workspace->id,
			'id' => $column->id,
			'title' => $column->title,
			'position' =>$column->position,
			])
		]);


		return view('workspace.show', [
			'workspace' => $workspace
		]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Column $column)
    {
        $this->authorize('view', $column);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Workspace $workspace, Column $column)
    {
        $this->authorize('update', $column);

		return view('column.edit', [
			'workspace' => $workspace,
			'column' => $column
		]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Workspace $workspace, Column $column)
    {
		$this->authorize('update', $column);
        $data = $request->validate([
			'title' => ['required', 'string', 'max:30'],
			'position' => ['nullable', 'integer'],
		]);

		$column->update($data);
		
		Redis::connection()->executeRaw([
			'PUBLISH',
			'updated_column',
			json_encode([
			'workspace_id' => $workspace->id,
			'id' => $column->id,
			'title' => $column->title,
			'position' =>$column->position,
			])
		]);


		return view('workspace.show', [
			'workspace' => $workspace
		]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Workspace $workspace, Column $column)
    {
		$this->authorize('delete', $column);
        $column->delete();

		Redis::connection()->executeRaw([
			'PUBLISH',
			'deleted_column',
			json_encode([
			'workspace_id' => $workspace->id,
			'id' => $column->id,
			])
		]);

		return view('workspace.show', [
			'workspace' => $workspace
		]);
    }
}
