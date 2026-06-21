<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Workspace;
use App\Enums\WorkspaceMemberRole;
use Illuminate\Auth\Access\Response;

class WorkspacePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Workspace $workspace): bool
    {
        return $user->roleInWorkspace($workspace) !== null;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Workspace $workspace): bool
    {
        $role = $user->roleInWorkspace($workspace);

		return in_array($role, [
			WorkspaceMemberRole::OWNER,
			WorkspaceMemberRole::ADMIN,
		]);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Workspace $workspace): bool
    {
        return $user->roleInWorkspace($workspace) === WorkspaceMemberRole::OWNER;;	
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Workspace $workspace): bool
    {
        return $user->id === $workspace->owner_id;;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Workspace $workspace): bool
    {
        return false;
    }
}
