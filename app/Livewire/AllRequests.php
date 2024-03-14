<?php

namespace App\Livewire;

use App\Livewire\Forms\ChangeRequestForm;
use App\Livewire\Forms\SprintForm;
use App\Models\Request;
use App\Models\Project;
use App\Models\Sprint;
use App\Models\Task;
use App\Services\TaskService;
use Livewire\Component;
use Livewire\Attributes\Rule;
use App\Livewire\Traits\WithServiceMethods;
use App\Services\Exceptions\ServiceException;
use App\Services\RequestService;
use Carbon\Carbon;
use Livewire\Attributes\On;
use App\Enums\NotificationType;


class AllRequests extends Component
{
    use WithServiceMethods;
    private string $serviceClass = RequestService::class;
    public ChangeRequestForm $form;
    public SprintForm $sprintForm;

    public $taskAlreadyInSprint;
    public $noActiveSprints;
    public $project;
    public $sprints;
    public $currentSprint;

    #[Rule('required|date|after_or_equal:today')]
    public $sprintStart = "";

    #[Rule('required|date|after_or_equal:sprintStart')]
    public $sprintEnd = "";

    #[Rule('required|string')]
    public $newRequest = "";

    #[Rule('required|string')]
    public $newRequestTitle = "";

    public $tableKey;


    public $selectedRows = [];
    public $isOpenedNewSprintForm = false;
    public $isOpenedNewRequestForm = false;

    public function mount(Project $project)
    {
        $this->sprints = Sprint::get();
        $this->project = $project;
        $this->form->projectId = $project->id;
        $this->tableKey = rand();
    }

    #[On('AddToNewSprint')]
    public function createSprintAndTasks($items)
    {
        try {
            $this->isOpenedNewSprintForm = true;
            $task = (new TaskService())->createTasksAndSprintFromRequest($this->project->id, $items, Carbon::parse($this->sprintForm->start), Carbon::parse($this->sprintForm->end));
        } catch (ServiceException $e) {
            $this->dispatch(
                'displayNotification',
                message: $e->getMessage(),
                type: NotificationType::Alert
            )->to(\App\Livewire\Notification::class);
        }
    }

    #[On('AddToSprint')]
    public function addTasksToSprint($items)
    {
        $sprint = Sprint::where('projectId', $this->project['id'])->latest()->first();
        try {
            (new TaskService())->createTasksForSprint($items, $sprint);
            $this->dispatch(
                'displayNotification',
                message: 'Tasks has been created and added!',
                type: NotificationType::Information
            )->to(\App\Livewire\Notification::class);
            $this->tableKey = rand();
        } catch (ServiceException $e) {
            $this->dispatch(
                'displayNotification',
                message: $e->getMessage(),
                type: NotificationType::Alert
            )->to(\App\Livewire\Notification::class);
        }
    }

    // public function createTask()
    // {
    // }

    // public function createNewRequest()
    // {
    //     $this->validate([
    //         'newRequestTitle' => "required|string",
    //         'newRequest' => "required|string"
    //     ]);
    //     Request::create([
    //         'projectId' => $this->project->id,
    //         'requesterId' => auth()->id(),
    //         'title' => $this->newRequestTitle,
    //         'request' => $this->newRequest,
    //     ]);
    //     $this->isOpenedNewRequestForm = false;
    //     return redirect()->to('/projects' . '/' . $this->project->id)
    //         ->with('status', 'Request created!');
    // }

    // public function createNewSprint()
    // {
    //     $this->validate([
    //         'sprintStart' => "required|date|after_or_equal:today",
    //         'sprintEnd' => "required|date|after_or_equal:sprintStart"
    //     ]);

    //     $lastSprint = Sprint::where('projectId', $this->project->id)->latest()->first();

    //     if ($lastSprint == null) {
    //         foreach ($this->selectedRows as $rowId) {
    //             $request = Request::where('id', $rowId)->first();
    //             $newSprint = Sprint::create([
    //                 'projectId' => $this->project->id,
    //                 'requestId' => $rowId,
    //                 'sprint' => 1,
    //                 'start' => $this->sprintStart,
    //                 'end' => $this->sprintEnd,
    //             ]);

    //             // Create a new task for the current sprint
    //             Task::create([
    //                 'projectId' => $this->project->id,
    //                 'requestId' => $rowId,
    //                 'sprintId' => $newSprint->id,
    //                 'task' => $request->request, // Replace with the actual task description
    //                 'start' => $this->sprintStart,
    //                 'end' => $this->sprintEnd,
    //             ]);
    //         }
    //     } elseif ($lastSprint['end'] < $this->sprintEnd && $this->sprintStart > $lastSprint['end']) {
    //         foreach ($this->selectedRows as $rowId) {
    //             $request = Request::where('id', $rowId)->first();
    //             $newSprint = Sprint::create([
    //                 'projectId' => $this->project->id,
    //                 'requestId' => $rowId,
    //                 'sprint' => $lastSprint['sprint'] + 1,
    //                 'start' => $this->sprintStart,
    //                 'end' => $this->sprintEnd,
    //             ]);

    //             // Create a new task for the current sprint
    //             Task::create([
    //                 'projectId' => $this->project->id,
    //                 'requestId' => $rowId,
    //                 'sprintId' => $newSprint->id,
    //                 'task' => $request->request, // Replace with the actual task description
    //                 'start' => $this->sprintStart,
    //                 'end' => $this->sprintEnd,
    //             ]);
    //         }
    //     }

    //     return redirect()->to('/projects' . '/' . $this->project->id)
    //         ->with('status', 'Project created!');
    // }
    public function closeNewSprintForm()
    {
        $this->isOpenedNewSprintForm = false;
    }

    public function closeNewRequestForm()
    {
        $this->isOpenedNewRequestForm = false;
    }

    public function toggleNewSprintForm()
    {
        $this->isOpenedNewSprintForm = true;
    }
    public function toggleNewRequestWindow()
    {
        $this->isOpenedNewRequestForm = true;
    }

    public function toggleSelect($id)
    {
        if (in_array($id, $this->selectedRows)) {
            $this->selectedRows = array_diff($this->selectedRows, [$id]);
        } else {
            $this->selectedRows[] = $id;
        }
    }

    public function rejectRequest($id)
    {
        Request::where('id', $id)->delete();
        return redirect()->to('/projects' . '/' . $this->project->id);
    }




    public function render()
    {
        return view(
            'livewire.all-requests',
            [
                'requests' => Request::where('projectId', $this->project->id)->get(),
                'project' => $this->project,
                'sprints' => $this->sprints,
            ]
        );
    }
}
