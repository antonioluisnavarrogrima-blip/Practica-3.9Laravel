<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'due_date',
        'status',
    ];
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    // N:N - Los usuarios que PARTICIPAN en el proyecto (tabla pivote project_user)
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_user')
            ->withPivot('role', 'contribution_hours')
            ->withTimestamps();
    }
}
