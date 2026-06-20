<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Models\WorkspaceMember;
use App\Models\User;
use App\Models\Column;
use App\Models\Task;

class Workspace extends Model
{
    protected $fillable = ['name', 'owner_id'];

    public function owner(): BelongsTo
    {
	return $this->belongsTo(User::class, 'owner_id');
    }

    public function members(): HasMany
    {
	return $this->hasMany(WorkspaceMember::class);
    }

    public function users(): BelongsToMany
    {
	return $this->belongsToMany(User::class, 'workspace_members')
		->withPivot('role')
		->withTimestamps();
    }

    public function columns(): HasMany
    {
	return $this->hasMany(Column::class);
    }

    public function tasks(): HasManyThrough 
    {
	return $this->hasManyThrough(Task::class, Column::class);
    }
}
