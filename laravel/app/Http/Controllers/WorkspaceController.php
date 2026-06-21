<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Policies\WorkspacePolicy;
use App\Enums\WorkspaceMemberRole;
use App\Models\WorkspaceMember;
use App\Enums\UserRole;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
		if ($request->user()->isAdmin()) {
    		$workspaces = Workspace::latest()->paginate(10);
		} else {
    		$workspaces = $request->user()->workspaces()->orderBy('updated_at')->paginate(10);
		}

		return view('workspace.index', compact('workspaces'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {	
        $data = $request->validate([
	    	'name'	=> 'required|string|max:30'
		]);

		$workspace = $request->user()->ownedWorkspaces()->create($data);

		WorkspaceMember::create([
			'workspace_id' => $workspace->id,
			'user_id' => $request->user()->id,
			'role' => WorkspaceMemberRole::OWNER,
		]);

		return redirect()->route('workspaces.show', $workspace)
			->with('success', 'Пространство было создано');
    }

    /**
     * Display the specified resource.
     */
    public function show(Workspace $workspace)
    {
		$this->authorize('view', $workspace);

        $workspace->load('owner', 'members.user', 'columns.tasks');
		return view('workspace.show', compact('workspace'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Workspace $workspace)
    {
        $this->authorize('update', $workspace);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Workspace $workspace)
    {
		$this->authorize('update', $workspace);
		
        $data = $request->validate([
	    	'name'	=> 'required|string|max:30'
		]);
		
		$workspace->update($data);

		return redirect()->route('workspaces.show', $workspace)
			->with('success', 'Пространство обновлено');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Workspace $workspace)
    {
		$this->authorize('delete', $workspace);

        $workspace->delete();

		return redirect()->route('workspaces.index')
			->with('success', 'Пространство удалено');
    }
}
