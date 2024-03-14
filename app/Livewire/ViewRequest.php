<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\Request;
use App\Models\Project;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Illuminate\Support\Facades\Session;
use App\Models\RepliedComment;
use App\Models\LikeDislike;

class ViewRequest extends Component
{
    public $currentRequest;

    #[Rule('required|string')]
    public $comment = "";
    public $comments;

    #[Rule('required|string')]
    public $repliedComment = "";


    public $showRepliedComments = [];

    public $openedReplyForms = [];

    public $openedReplyReplingForms = [];

    public $repliedComments;

    public $likesDislikes = [];

    public $isRepliedCommentsShown = false;

    public function addComment()
    {
        $this->validate([
            'comment' => "required|string",
        ]);
        Comment::create([
            'commenterId' => auth()->id(),
            'comment' => $this->comment,
            'commentable_id' => $this->currentRequest->id,
            'commentable_type' => Request::class,
        ]);
        return redirect()->to('/projects/view' . '/' . $this->currentRequest->id);
    }

    public function openReplyForm($commentId)
    {
        array_push($this->openedReplyForms, $commentId);
    }

    public function closeReplyForm($commentId)
    {
        $this->openedReplyForms = array_diff($this->openedReplyForms, [$commentId]);
    }

    public function openReplyReplingForm($commentId)
    {
        return array_push($this->openedReplyReplingForms, $commentId);
    }

    public function closeReplyReplingForm($commentId)
    {
        $this->openedReplyReplingForms = array_diff($this->openedReplyReplingForms, [$commentId]);
    }

    public function getCommentReplies($commentId)
    {
        $this->isRepliedCommentsShown = !$this->isRepliedCommentsShown;
        if (!in_array($commentId, $this->showRepliedComments)) {
            // $this->isRepliedCommentsShown = true;
            array_push($this->showRepliedComments, $commentId);
        } else {
            // $this->isRepliedCommentsShown = false;
            $this->showRepliedComments = array_diff($this->showRepliedComments, [$commentId]);
        }
        // dd(Comment::where(['replyTo' => $commentId])
        // ->join('users', 'comments.commenterId', '=', 'users.id')
        // ->select('comments.*', 'users.name as replierName')
        // ->get());
        $this->repliedComments = Comment::where(['replyTo' => $commentId])
            ->join('users', 'comments.commenterId', '=', 'users.id')
            ->select('comments.*', 'users.name as replierName')
            ->get();
    }

    public function getLikesDislikes()
    {
        $this->likesDislikes = [];
        foreach ($this->comments as $comment) {
            $likes = LikeDislike::where(['likeable_id' => $comment->id, 'likeable_type' => $comment->commentable_type, 'action' => '1'])->count();
            $dislikes = LikeDislike::where(['likeable_id' => $comment->id, 'likeable_type' => $comment->commentable_type, 'action' => '-1'])->count();
            $this->likesDislikes[] = ['commentId' => $comment->id, 'likes' => $likes, 'dislikes' => $dislikes];
        }
    }

    public function submitReply($commentId)
    {
        $this->validate([
            'repliedComment' => "required|string",
        ]);
        Comment::create([
            'projectId' => $this->currentRequest->projectId,
            'commenterId' => auth()->id(),
            'comment' => $this->repliedComment,
            'replyTo' => $commentId,
            'commentable_id' => $this->currentRequest->id,
            'commentable_type' => Request::class
        ]);
        // $this->isReplyFormOpened[$commentId] = false;
    }

    public function likeComment($commentId)
    {
        $this->likesDislikes = [];
        $currentComment = LikeDislike::where(['commenterId' => auth()->id(), 'likeable_id' => $commentId, 'likeable_type' => Request::class])->first();
        if ($currentComment == null) {
            LikeDislike::create([
                'commenterId' => auth()->id(),
                'likeable_id' => $commentId,
                'likeable_type' => Request::class,
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
        $this->getLikesDislikes();
    }
    public function dislikeComment($commentId)
    {
        $this->likesDislikes = [];
        $currentComment = LikeDislike::where(['commenterId' => auth()->id(), 'likeable_id' => $commentId, 'likeable_type' => Request::class])->first();
        // dd($currentComment->action);
        if ($currentComment == null) {
            LikeDislike::create([
                'commenterId' => auth()->id(),
                'likeable_id' => $commentId,
                'likeable_type' => Request::class,
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
        $this->getLikesDislikes();
    }


    public function mount(Request $request)
    {

        $this->currentRequest = Request::where('id', $request->id)->first();
        $this->comments = Comment::where(['projectId' => $this->currentRequest->projectId, 'commentable_id' => $this->currentRequest->id, 'replyTo' => null])
            ->join('users', 'comments.commenterId', '=', 'users.id')
            ->select('comments.*', 'users.name as commenterName')
            ->get();
        if (!is_null($this->comments)) {
            $this->getLikesDislikes();
        }
        return view('livewire.view-request', [
            'currentRequest' => $this->currentRequest,
            'comments' => $this->comments
        ]);
    }

    public function render(Request $request)
    {
        // $this->currentRequest = Request::where('id', $request->id)->first();
        // $this->comments = Comment::where(['projectId' => $this->currentRequest->projectId, 'commentable_id' => $this->currentRequest->id])
        //     ->join('users', 'comments.commenterId', '=', 'users.id')
        //     ->select('comments.*', 'users.name as commenterName')
        //     ->get();
        //     if (!is_null($this->comments)) {
        //         $this->getLikesDislikes();
        //     }
        return view('livewire.view-request');
    }
}
