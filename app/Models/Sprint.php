<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sprint extends Model
{
    use HasFactory;

    protected $table = 'sprints';

    protected $fillable = [
        'projectId',
        'requestId',
        'sprint',
        'start',
        'end'
    ];

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'sprintId');
    }
}
