<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class RepliedComment extends Model
{
    use HasFactory;
    protected $fillable = [
        'commentId',
        'replierId',
        'repliedComment',
    ];

    public function likesdislikes(): MorphMany
    {
        return $this->morphMany(LikeDislike::class, 'likeable');
    }
}
