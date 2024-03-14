<?php

namespace App\Livewire;

use App\Models\Request;
use App\Models\Project;
use Livewire\Attributes\Rule;
use App\Models\User;
use Livewire\Component;
class Projects extends Component
{
    public $projects;

    #[Rule('required|string')]
    public $newRequest;
    public $currentProjectId;
    public $isRequestWindowOpened = false;

    // public function openRequestWindow() {
    //     $this->isRequestWindowOpened = true;
    // }

    // public function createNewRequest($id) {
    //     $this->currentProjectId = $id;
    //     $this->validate();
    //     $newCreatedRequest = ChangeRequest::create([
    //         'projectId' => $this->currentProjectId,
    //         'requesterId' => auth()->id(),
    //         'request' => $this->newRequest,
    //     ]);
    //     $this->isRequestWindowOpened = false;
    //     return redirect()->to('/projects')->with([
    //         'projectId' => $this->currentProjectId,
    //         'request' => $this->newRequest,
    //     ]);
    // }

    public function viewProjectDetails($id){
        $project = Project::find($id);
        return view('livewire.task-details');
    }

    public function render()
    {
        $role = User::where("id", auth()->id())->value('role');
        $requests = Request::all();
        $this->projects = Project::all();
        return view('livewire.projects',[
            'projects' => $this->projects,
            'role' => $role,
            'requests' => $requests,
        ]);
    }
}
