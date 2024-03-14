<?php

namespace App\Livewire;

use App\Models\LikeDislike;
use App\Models\Project;
use App\Models\RepliedComment;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Rule;
use App\Models\Comment;
use App\Models\TaskUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Services\CommentService;
use App\Services\Exceptions\ServiceException;
use App\Services\TaskService;

class ViewTask extends Component
{
    public $currentTask;
    public $currentProject;

    #[Rule('required|string')]
    public $comment = "";

    #[Rule('required|string')]
    public $repliedComment = "";
    public $comments;

    public $showRepliedComments = [];

    public $openedReplyForms = [];

    public $repliedRepliedComments = [];

    public $openedReplyReplingForms = [];

    // public $repliedComments;

    public $showRepliesForCommentIds = [];

    public $likesDislikes;

    public $likers = [];

    public $mainComments;

    public $repliedComments;

    public $isRepliedCommentsShown = false;

    public $openLikersForm = false;

    private $commentService;

    public $commentableType = Task::class;

    public $isEditTaskFormOpened = false;

    public $newTaskDescription = "";

    public $assignedUsers = [];

    public $taskStart;

    public $taskEnd;


    public function mount(Task $task, Project $project)
    {
        $this->currentProject = $project;
        $this->currentTask = $task;
        $this->taskStart = $task->start;
        $this->taskEnd = $task->end;
        $usersIds = TaskUser::where('task_id', $task->id)->get()->toArray();
        foreach ($usersIds as $userId) {
            $this->assignedUsers = User::whereIn('id', $userId)->get()->toArray();
        }
        $this->comments = Comment::where(['commentable_id' => $task->id, 'replyTo' => null])
            ->join('users', 'comments.commenterId', '=', 'users.id')
            ->select('comments.*', 'users.name as commenterName')
            ->get();
        // $this->commentService = $CommentService;
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
        $this->repliedComments = Comment::where(['replyTo' => $commentId])
            ->join('users', 'comments.commenterId', '=', 'users.id')
            ->select('comments.*', 'users.name as replierName')
            ->get();
        foreach ($this->repliedComments as $repliedComment) {
            array_push(
                $this->repliedRepliedComments,
                Comment::where(['replyTo' => $repliedComment->id])
                    ->join('users', 'comments.commenterId', '=', 'users.id')
                    ->select('comments.*', 'users.name as replierName')->get()
            );
        }
    }

    public function editTask($id, $task)
    {
        $this->isEditTaskFormOpened = true;
        $this->newTaskDescription = $task;
    }

    public function changeTaskStage($action)
    {
        $taskService = (new TaskService());
        if ($action == 'toggleActiveStatus') {
            $this->currentTask = $taskService->toggleTaskActiveStatus($this->currentTask['id']);
        } elseif ($action == 'moveForward') {
            $this->currentTask = $taskService->moveTaskStageForwardOrBack($this->currentTask['id']);
        } elseif ($action == 'moveBack') {
            $this->currentTask = $taskService->moveTaskStageForwardOrBack($this->currentTask['id'], false);
        }
    }

    public function saveChangedTask($id)
    {
        try {
            $this->currentTask = (new TaskService())->loadFieldValues(['id' => $id, 'task' => $this->newTaskDescription, 'start' => $this->taskStart, 'end' => $this->taskEnd])->update();
            $this->isEditTaskFormOpened = false;
            // $task = Task::findOrFail($id);
            // $task->task = $this->newTaskDescription;
            // $task->save();
        } catch (ServiceException $e) {
            $this->addError('ValidationError', $e->getMessage());
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

    public function closeShowLikers()
    {
        $this->likers = [];
        $this->openLikersForm = false;
    }

    public function submitReply($commentId)
    {
        $this->validate([
            'repliedComment' => "required|string",
        ]);
        $commentService = new CommentService();
        $commentService->confirmSubmitReply($commentId, $this->repliedComment, $this->currentProject, $this->currentTask, auth()->id());
    }

    // public function addComment(CommentService $commentService)
    // {
    //     // dd($commentService);
    //     $this->validate([
    //         'comment' => "required|string",
    //     ]);
    //     Comment::addNewComment($this->currentProject->id, auth()->id(), $this->comment, $this->currentTask->id, Task::class);
    // }

    public function openReplyForm($commentId)
    {
        return array_push($this->openedReplyForms, $commentId);
    }

    public function closeReplyForm($commentId)
    {
        $this->openedReplyForms = array_diff($this->openedReplyForms, [$commentId]);
    }


    public function likeComment($commentId)
    {
        $this->likesDislikes = [];
        $commentService = new CommentService();
        $commentService->likeSelectedComment($commentId, auth()->id());
        $this->getLikesDislikes();
    }
    public function dislikeComment($commentId)
    {
        $this->likesDislikes = [];
        $commentService = new CommentService();
        $commentService->dislikeSelectedComment($commentId, auth()->id());
        $this->getLikesDislikes();
    }

    // CommentService $commentService
    public function render()
    {
        $this->likesDislikes = [];


        // dd($this->repliedComments);
        $this->getLikesDislikes();
        // foreach($this->mainComments as $comment){
        //     dd($comment);
        // }
        // $this->commentService->getLikesDislikes();
        return view('livewire.view-task');
    }


    public function getLikesDislikes()
    {
        // $commentService = new CommentService();
        $this->likesDislikes = [];
        // $this->likesDislikes = array_merge(Comment::getCommentLikesDislikes($this->mainComments));
    }
}
