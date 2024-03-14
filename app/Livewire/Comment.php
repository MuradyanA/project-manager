<?php

namespace App\Livewire;

use App\Livewire\Forms\CommentForm;
use App\Services\CommentService;
use Livewire\Component;
use App\Models\Task;
use App\Models\Comment as CommentModel;
use App\Models\Project;
use App\Models\LikeDislike;
use App\Models\User;

class Comment extends Component
{
    private string $commentServiceClass = CommentService::class;

    public CommentForm $form;

    public string $commentableType = '';

    public ?int $commentableId = null;

    public ?int $replyTo = null;

    public ?int $mainComment = null;

    public $comments;

    public $currentTask;

    public $likesDislikes = [];

    public $openedReplyForms;

    public $showRepliedComments;

    public $repliedRepliedComments;

    public $repliedComments;

    public $isRepliedCommentsShown;

    public $openLikersForm = false;

    public $likers = [];

    public $repliedComment;

    public $openedReplyReplingForms = [];

    public $currentProject;


    public function mount($commentableType, $commentableId, $comments, $currentTask, $likesDislikes, $openedReplyForms, $showRepliedComments, $repliedRepliedComments, $isRepliedCommentsShown, $currentProject)
    {
        $this->commentableType = $commentableType;
        $this->commentableId = $commentableId;
        $this->comments = $comments;
        $this->currentTask = $currentTask;
        $this->currentProject = $currentProject;
        $this->likesDislikes = $likesDislikes;
        $this->openedReplyForms = $openedReplyForms;
        $this->showRepliedComments = $showRepliedComments;
        $this->repliedRepliedComments = $repliedRepliedComments;
        $this->isRepliedCommentsShown = $isRepliedCommentsShown;
    }

    public function render()
    {
        $mainComments = CommentModel::whereNull('main_comment')->where(['commentable_id' => $this->commentableId, 'commentable_type' => Task::class])->select('comments.id', 'comments.commenterId', 'comments.comment', 'comments.replyTo', 'comments.main_comment', 'comments.commentable_id', 'comments.commentable_type', 'users.name as commenterName')->join('users', 'users.id', '=', 'comments.commenterId')->orderBy('comments.created_at', 'desc')->get();
        $repliedComments = CommentModel::whereIn('main_comment', $mainComments->pluck('id')->all())
            ->join('users', 'comments.commenterId', '=', 'users.id')
            ->select('comments.*', 'users.name as commenter_name')
            ->orderBy('comments.created_at', 'desc')
            ->get();
        return view('livewire.comment', ['mainComments' => $mainComments, 'repliedComments' => $repliedComments]);
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
            $this->repliedRepliedComments = array_diff($this->repliedRepliedComments, $this->repliedRepliedComments);
        } else {
            // $this->isRepliedCommentsShown = false;
            $this->showRepliedComments = array_diff($this->showRepliedComments, [$commentId]);
        }
        $this->repliedComments = CommentModel::where(['replyTo' => $commentId])
            ->join('users', 'comments.commenterId', '=', 'users.id')
            ->select('comments.*', 'users.name as replierName')
            ->get();
        foreach ($this->repliedComments as $repliedComment) {
            array_push(
                $this->repliedRepliedComments,
                CommentModel::where(['replyTo' => $repliedComment->id])
                    ->join('users', 'comments.commenterId', '=', 'users.id')
                    ->select('comments.*', 'users.name as replierName')->get()
            );
        }
    }

    public function showLikers($commentId)
    {
        $this->openLikersForm = true;
        $likers = LikeDislike::where(['likeable_id' => $commentId, 'likeable_type' => Task::class, 'action' => 1])->pluck('commenterId')->toArray();
        foreach ($likers as $liker) {
            $likerName = User::where('id', $liker)->pluck('name')->first();
            $this->likers[] = $likerName;
        }
    }

    public function submitReply($commentId)
    {
        $this->validate([
            'repliedComment' => "required|string",
        ]);
        $commentService = new CommentService();
        $commentService->confirmSubmitReply($commentId, $this->repliedComment, $this->currentTask, auth()->id());
    }

    public function closeShowLikers()
    {
        $this->likers = [];
        $this->openLikersForm = false;
    }

    public function save()
    {
        $this->form->validate();
        $commentService = new CommentService();
        $commentService->loadFieldValues(
            array_merge(
                $this->form->all(),
                [
                    'commentable_id' => $this->commentableId,
                    'commentable_type' => $this->commentableType,
                    'replyTo' => $this->replyTo,
                    'main_comment' => $this->mainComment
                ]
            )
        )->create();
        $this->replyTo = null;
        $this->mainComment = null;
    }

    public function openReplyForm($commentId)
    {
        return array_push($this->openedReplyForms, $commentId);
    }

    public function closeReplyForm($commentId)
    {
        $this->openedReplyForms = array_diff($this->openedReplyForms, [$commentId]);
    }

    public function reply($commentId)
    {
        $parentComment = CommentModel::findOrFail($commentId);
        $this->replyTo = $parentComment->id;
        $this->mainComment = is_null($parentComment->main_comment) ? $parentComment->id : $parentComment->main_comment;
    }
}
