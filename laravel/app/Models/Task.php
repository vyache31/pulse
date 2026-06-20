<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Column;
use App\Models\User;

class Task extends Model
{
    protected $fillable = [
		'column_id',
		'title',
		'description',
		'tags',
		'assigned_to'
    ];

    public function column(): BelongsTo
    {
	return $this->belongsTo(Column::class);
    }

    public function doer(): BelongsTo
    {
	return $this->belongsTo(User::class, 'assigned_to');
    }
}
