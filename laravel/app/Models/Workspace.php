<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workspace extends Model
{
    protected $fillable = ['title', 'owner_id'];

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
}
