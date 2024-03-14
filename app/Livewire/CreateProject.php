<?php

namespace App\Livewire;

use App\Models\Project;
use Livewire\Component;
use Livewire\Attributes\Rule;


class CreateProject extends Component
{
    #[Rule('required|string')]
    public $projectName = "";

    #[Rule('required|string')]
    public $description = "";
    
    #[Rule('int|exists:customers,id')]
    public $customerId = null;
    
    public function createProject(){
        $this->validate();
        Project::create([
            'name' => $this->projectName,
            'description' => $this->description,
            'customerId' => $this->customerId
        ]);
        return redirect()->to('/projects/create')
               ->with('status', 'Project Created!');
    }
    
    
    public function render()
    {
        return view('livewire.create-project');
    }
}
