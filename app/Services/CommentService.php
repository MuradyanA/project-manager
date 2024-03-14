<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Task;
use App\Models\LikeDislike;

class CommentService extends BaseService
{
    protected const VALIDATION_RULES = [
        'comment' => 'required',
        'commentable_type' => 'required',
        'commentable_id' => 'required',
        'main_comment' => 'nullable|int|exists:comments,id',
        'replyTo' => 'nullable|int|exists:comments,id',
    ];
    // protected $likesDislikes = [];

    protected const ENTITY_MODEL_CLASS = Comment::class;
    //Move to Comments model

    public function beforeAction($actionName){
        switch ($actionName) {
            case 'create':
            case 'update':
                $this->fields['commenterId'] = auth()->id(); 
                break;
        }
    }
    // new App\Services\CommentService(['comment' => 'Hello','commentable_type' => 'App\Models\Task','commentable_id' =>'1','main_comment' => '1'])->create();
    public function likeSelectedComment($commentId, $userId)
    {
        $currentComment = LikeDislike::where(['commenterId' => $userId, 'likeable_id' => $commentId, 'likeable_type' => Task::class])->first();
        if ($currentComment == null) {
            LikeDislike::create([
                'commenterId' => $userId,
                'likeable_id' => $commentId,
                'likeable_type' => Task::class,
                'action' => '1'
            ]);
        } elseif ($currentComment->action == 1) {
            $currentComment->action = "0";
            $currentComment->save();
        } elseif ($currentComment->action == -1) {
            $currentComment->action = "1";
            $currentComment->save();
        } elseif ($currentComment->action == 0) {
            $currentComment->action = "1";
            $currentComment->save();
        }
    }

    public function dislikeSelectedComment($commentId, $userId)
    {
        $currentComment = LikeDislike::where(['commenterId' => $userId, 'likeable_id' => $commentId, 'likeable_type' => Task::class])->first();
        // dd($currentComment->action);
        if ($currentComment == null) {
            LikeDislike::create([
                'commenterId' => $userId,
                'likeable_id' => $commentId,
                'likeable_type' => Task::class,
                'action' => '-1'
            ]);
        } elseif ($currentComment->action == 1) {
            $currentComment->action = "-1";
            $currentComment->save();
        } elseif ($currentComment->action == -1) {
            $currentComment->action = "0";
            $currentComment->save();
        } elseif ($currentComment->action == 0) {
            $currentComment->action = "-1";
            $currentComment->save();
        }
    }

    public function confirmSubmitReply($commentId, $repliedComment, $thisTask, $userId)
    {
        $parentComment = Comment::findOrFail($commentId);
        Comment::create([
            'commenterId' => $userId,
            'comment' => $repliedComment,
            'replyTo' => $parentComment->id,
            'main_comment' => is_null($parentComment->main_comment) ? $parentComment->id : $parentComment->main_comment,
            'commentable_id' => $thisTask->id,
            'commentable_type' => Task::class
        ]);
    }


}