<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_manager_id',
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
        'name_tasks',
    ];

    /**
     * Get all of the tasks for the Project
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the project_manager that owns the Project
     */
    public function project_manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'project_manager_id', 'id');
    }
}
