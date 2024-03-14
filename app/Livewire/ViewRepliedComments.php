<?php

namespace App\Livewire;

use App\Models\Comment;
use App\Models\RepliedComment;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Rule;

class ViewRepliedComments extends Component
{
    public $replied_comment;
    public $replier;

    public $projectId;

    #[Rule('required|string')]
    public $comment = "";

    public function addReply(){
        $this->validate();
        RepliedComment::create([
            'commentId' => $this->replied_comment->id,
            'replierId' => auth()->id(),
            'repliedComment' => $this->comment
        ]);
    }    
    
    public function mount(RepliedComment $replied_comment)
    {
        $this->replied_comment = $replied_comment;
        $comment = Comment::where('id', $replied_comment->commentId)->first();
        $this->projectId = $comment->projectId;
        $this->replier = User::where('id', $replied_comment->replierId)->first();
    }

    public function render(RepliedComment $repliedComment)
    {
        return view('livewire.view-replied-comments');
    }
}
