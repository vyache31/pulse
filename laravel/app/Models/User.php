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
use App\Enums\Role;
use App\Models\Workspace;
use App\Models\WorkspaceMember;
use App\Models\Task;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = ['username', 'email', 'password', 'role'];

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
 	    'role' => Role::class,
        ];
    }

    public function ownedWorkspaces(): HasMany
    {
	return $this->hasMany(Workspace::class, 'owner_id'));
    }

    public function workspaces(): BelongsToMany
    {
	return $this->belongsToMany(Workspace::class, 'workspace_members')
		->withPivot('role')->withTimestamps();
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