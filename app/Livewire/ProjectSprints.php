<?php

namespace App\Livewire;

use App\Enums\NotificationType;
use App\Models\Project;
use App\Models\Request;
use App\Models\Sprint;
use App\Models\Task;
use App\Models\TaskUser;
use App\Models\User;
use App\Services\Exceptions\ServiceException;
use App\Services\SprintService;
use App\Services\TaskUserAssociationService;
use Livewire\Component;
use Livewire\Attributes\Rule;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;

class ProjectSprints extends Component
{
    public $project;
    public $isAssignmentFormOpened = false;
    public $users = [];
    public $sprintNumber;
    public Collection $sprints;
    public Sprint $currentSprint;
    public int $currentSprintID;
    // public $assignModel = TaskUser::class;
    public $searchModel = User::class;
    public $searchFields = ['name'];
    public $fieldsToShow = ['name', 'role'];
    public $selectedTaskId;
    public $selectedIds = [];
    public $selectedUsers = [];
    public $usersId = [];

    #[Rule('int|exists:sprints,id')]
    public $sprintId;

    public $tableKey = 'Sprints';

    public function mount(Project $project)
    {
        $this->project = $project;
        $this->sprints = Sprint::where('projectId', $this->project->id)->get();
        $lastSprintID = $this->sprints->max('id');
        $this->currentSprint = Sprint::find($lastSprintID);
        $this->currentSprint->load('tasks');
        $this->currentSprintID = $this->currentSprint->id;
        $users = User::where('role', '!=', 'Project Owner')->get();
        $this->users = $users;
        // $this->users = User::where('role', '!=', 'Project Owner')->get();
        $this->sprintNumber = Sprint::orderBy('created_at', 'desc')->value('sprint');
    }
    #[On('itemSelected')]
    public function itemCreated($item, $searchModel)
    {
        try {
            (new TaskUserAssociationService())->createAssociation($item['id'], $this->selectedTaskId);
        } catch (ServiceException $e) {
            $this->addError('ValidationError', $e->getMessage());
        }
    }
    #[On('itemRemoved')]

    public function itemRemoved($item, $searchModel)
    {
        (new TaskUserAssociationService())->deleteAssociation($item['id'], $this->selectedTaskId);
    }

    // #[On('post-created')] itemRemoved, itemCreated from multipleChoiceInput

    public function updated($propertyName)
    {
        // dd($propertyName);
        if ($propertyName == 'currentSprintID') {
            $this->currentSprint = Sprint::find($this->currentSprintID)->load('tasks');
        }
    }

    #[On('Delete')]
    public function deleteSprint($items)
    {
        try {
            (new SprintService())->loadFieldValues(['id' => $items[0]])->delete();
            $this->tableKey = rand();
        } catch (ServiceException $e) {
            // $this->addError('ValidationError', $e->getMessage());
            $this->dispatch(
                'displayNotification',
                message: $e->getMessage(),
                type: NotificationType::Alert
            )->to(\App\Livewire\Notification::class);
        }
    }

    public function toggleAssignmentForm($id)
    {
        $this->isAssignmentFormOpened = true;
        $this->selectedTaskId = $id;
        $this->selectedIds = TaskUser::where('task_id', $id)->pluck('user_id')->toArray();
    }

    public function toggleUser($userId)
    {
        // Toggle the presence of $userId in the $selectedUsers array
        if (in_array($userId, $this->selectedUsers)) {
            // User ID is in the array, so remove it
            $this->selectedUsers = array_diff($this->selectedUsers, [$userId]);
        } else {
            // User ID is not in the array, so add it
            $this->selectedUsers[] = $userId;
        }
    }

    public function closeAssignmentForm()
    {
        $this->isAssignmentFormOpened = false;
        $this->selectedTaskId = null;
    }

    // public function assignToTask()
    // {
    //     $today = (new DateTime())->format('Y-m-d');
    //     $task = Task::where('id', $this->selectedTaskId)
    //         ->where('start', '<=', $today)
    //         ->where('end', '>=', $today)
    //         ->first();
    //     foreach ($this->selectedUsers as $userId) {
    //         TaskUser::create([
    //             'user_id' => $userId,
    //             'task_id' => $task->id
    //         ]);
    //     }
    //     // Reset the form or perform any other necessary actions
    //     $this->selectedUsers = [];
    //     return redirect()->to('/projects' . '/' . $this->project->id . '/sprints');
    // }

    public function render()
    {
        return view('livewire.project-sprints');
    }
}
