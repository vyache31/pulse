<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkspaceMember;
use App\Models\Workspace;
use App\Enums\WorkspaceMemberRole;

class WorkspaceMemberPolicy
{
	private function canManage(User $user, Workspace $workspace): bool
    {
        $role = $user->roleInWorkspace($workspace);

        return in_array($role, [
            WorkspaceMemberRole::OWNER,
            WorkspaceMemberRole::ADMIN,
        ]);
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, WorkspaceMember $member): bool
    {
        return $this->canManage($user, $member->workspace);
    }

    public function create(User $user, Workspace $workspace): bool
    {
        return $this->canManage($user, $workspace);
    }

    public function update(User $user, WorkspaceMember $member): bool
    {
        return $this->canManage($user, $member->workspace);
    }

    public function delete(User $user, WorkspaceMember $member): bool
    {
        return $this->canManage($user, $member->workspace);
    }
}