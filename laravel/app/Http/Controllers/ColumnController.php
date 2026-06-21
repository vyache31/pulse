<?php

namespace App\Http\Controllers;

use App\Models\Column;
use App\Models\Workspace;
use Illuminate\Http\Request;

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
    public function create()
    {
        //
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

		$workspace->columns()->create([
			'title' => $data['title'],
			'position' => $lastColumnIndex + 1,
		]);

		return back();
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
    public function edit(Column $column)
    {
        $this->authorize('update', $column);
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

		return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Column $column)
    {
		$this->authorize('delete', $column);
        $column->delete();

		return back();
    }
}
