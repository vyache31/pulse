<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Workspace;
use App\Models\User;
use App\Enums\WorkspaceMemberRole;

class WorkspaceMember extends Model
{
    use HasFactory;
    protected $fillable = ['workspace_id', 'user_id', 'role'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
 	    'role' => WorkspaceMemberRole::class,
        ];
    }


    public function workspace(): BelongsTo
    {
     	return $this->belongsTo(Workspace::class);
    }

    public function user(): BelongsTo
    {
	return $this->belongsTo(User::class);
    }
}
