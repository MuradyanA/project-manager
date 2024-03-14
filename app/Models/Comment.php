<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Comment extends Model
{
    use HasFactory;

    protected $likesDislikes = [];

    
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function likesdislikes(): MorphMany
    {
        return $this->morphMany(LikeDislike::class, 'likeable');
    }

    public static function getCommentLikesDislikes($mainComments)
    {
        foreach ($mainComments as $comment) {
            $likes = LikeDislike::where(['likeable_id' => $comment->id, 'likeable_type' => $comment->commentable_type, 'action' => '1'])->count();
            $dislikes = LikeDislike::where(['likeable_id' => $comment->id, 'likeable_type' => $comment->commentable_type, 'action' => '-1'])->count();
            $likesDislikes[] = ['commentId' => $comment->id, 'likes' => $likes, 'dislikes' => $dislikes];
        }
        // return $likesDislikes;
    }

    public static function addNewComment($commenterId, $comment, $commentableId, $commentableType)
    {
        Comment::create([
            'commenterId' => $commenterId,
            'comment' => $comment,
            'commentable_id' => $commentableId,
            'commentable_type' => $commentableType,
        ]);
    }

    protected $fillable = [
        'projectId',
        'commenterId',
        'comment',
        'replyTo',
        'commentable_id',
        'commentable_type',
        'main_comment'
    ];
}
