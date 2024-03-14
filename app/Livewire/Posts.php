<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;

class Posts extends Component
{
    public function render()
    {
        return view('livewire.posts')->with([
            'posts' => Post::all()
        ]);
    }
}
