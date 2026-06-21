<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Enums\UserRole;
use App\Models\Workspace;
use App\Models\WorkspaceMember;
use App\Models\Task;
use App\Enums\WorkspaceMemberRole;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = ['username', 'email', 'password', 'role', 'github_id'];

    protected $hidden = ['password', 'remember_token'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
 	    	'role' => UserRole::class,
        ];
    }

	public function isAdmin(): bool
	{
		return $this->role === UserRole::ADMIN;
	}

    public function ownedWorkspaces(): HasMany
    {
		return $this->hasMany(Workspace::class, 'owner_id');
    }

    public function workspaces(): BelongsToMany
    {
		return $this->belongsToMany(Workspace::class, 'workspace_members')
			->withPivot('role')->withTimestamps();
    }

	public function roleInWorkspace(Workspace $workspace): ?WorkspaceMemberRole
	{
		$member = $this->workspaceMembers()
			->where('workspace_id', $workspace->id)
			->first();

		return $member?->role;
	}

    public function workspaceMembers(): HasMany
    {
		return $this->hasMany(WorkspaceMember::class);
    }

    public function tasks(): HasMany
    {
		return $this->hasMany(Task::class, 'assigned_to')->latest();
    }
}