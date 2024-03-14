<?php

namespace App\Livewire;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreatePost extends Component
{
    public $title = 'Post title...';
 
    public function save() 
    {
        $post = Post::create([
            'title' => $this->title
        ]);
 
        return redirect()->to('/posts')
             ->with('status', 'Post created!');
    }

    public function render()
    {
        return view('livewire.create-post');
    }
}
