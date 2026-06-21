<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Column;
use Illuminate\Http\Request;

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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Column $column)
    {
		$this->authorize('create', $column);
		
		$tags = collect(explode(',', $request->tags))
			->map(fn ($tag) => trim($tag))
			->filter()
			->values()
			->all();

        $data = $request->validate([
			'title' => ['required', 'string', 'max:30'],
			'description' => ['nullable', 'string', 'max:255'],
			'tags' => ['nullable', 'array', 'max:10'],
			'tags.*' => ['string', 'max:15'],
		]);

		$column->tasks()->create([
			'title' => $data['title'],
			'description' => $data['description'],
			'tags' => $tags ?? [],
		]);

		return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $this->authorize('update', $task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);
		$data = $request->validate([
			'title' => ['required', 'string', 'max:30'],
			'description' => ['nullable', 'string', 'max:255'],
			'tags' => ['nullable', 'array', 'max:10'],
			'tags.*' => ['string', 'max:15'],
		]);

		$task->update($data);

		return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();

		return back();

    }
}
