<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Models\User;
use App\Models\WorkspaceMember;
use Illuminate\Http\Request;
use App\Enums\WorkspaceMemberRole;

class WorkspaceMemberController extends Controller
{
    public function index(Workspace $workspace)
    {
		$this->authorize('view', WorkspaceMember::class);
        $members = WorkspaceMember::with('user')
            ->where('workspace_id', $workspace->id)
            ->get();

        return view('workspace.members.index', compact('workspace', 'members'));
    }

    public function store(Request $request, Workspace $workspace)
    {
		$this->authorize('create', $workspace);
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'role' => ['nullable']
        ]);

        $exists = WorkspaceMember::where('workspace_id', $workspace->id)
            ->where('user_id', $data['user_id'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['user' => 'Пользователь уже в workspace']);
        }

        WorkspaceMember::create([
            'workspace_id' => $workspace->id,
            'user_id' => $data['user_id'],
            'role' => $data['role'] ?? WorkspaceMemberRole::MEMBER,
        ]);

        return back();
    }

    public function update(Request $request, WorkspaceMember $workspaceMember)
    {
		$this->authorize('update', $workspaceMember);
        $data = $request->validate([
            'role' => ['required']
        ]);

        $workspaceMember->update($data);

        return back();
    }

    public function destroy(Workspace $workspace, WorkspaceMember $workspaceMember)
    {
		$this->authorize('delete', $workspaceMember);

		if ($workspaceMember->role == WorkspaceMemberRole::OWNER) {
			return back()->withErrors(['workspace_member' => 'Нельзя выгнать владельца']);
		}
        $workspaceMember->delete();

        return back();
    }
}