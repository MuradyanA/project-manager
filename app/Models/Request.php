<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Request extends Model
{
    use HasFactory;

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    protected $table = 'change_request';
    protected $fillable = [
        'projectId',
        'requesterId',
        'title',
        'request',
    ];
}
// projectId, commenterId, comment, commentableId, commentableType