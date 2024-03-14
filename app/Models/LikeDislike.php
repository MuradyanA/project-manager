<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class LikeDislike extends Model
{
    use HasFactory;

    protected $table = 'likes_dislikes';

    protected $fillable = [
        'commenterId',
        'likeable_id',
        'likeable_type',
        'action',
    ];

    public function likeable(): MorphTo
    {
        return $this->morphTo();
    }
}
