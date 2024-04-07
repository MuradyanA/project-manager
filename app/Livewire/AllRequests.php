<?php

namespace App\Livewire;

use App\Livewire\Forms\ChangeRequestForm;
use App\Livewire\Forms\SprintForm;
use App\Models\Request;
use App\Models\Project;
use App\Models\Sprint;
use App\Models\Task;
use App\Services\HyperLinkCreator;
use App\Services\TaskService;
use App\Services\ViewDetails\ButtonCreator;
use Livewire\Component;
use Livewire\Attributes\Rule;
use App\Livewire\Traits\WithServiceMethods;
use App\Livewire\Traits\WithViewDetailsView;
use App\Services\Exceptions\ServiceException;
use App\Services\RequestService;
use Carbon\Carbon;
use Livewire\Attributes\On;
use App\Enums\NotificationType;
use App\Services\Forms\FormBuilder;
use App\Services\ViewDetails\DetailsViewBuilder;
use App\Services\Forms\FormButton;
use App\Services\Forms\FormTextArea;
use App\Services\Forms\FormTextInput;
use App\Services\ViewDetails\CommentsViewData;
use App\Services\ViewDetails\InfoViewData;
use App\Services\ViewDetails\BlockInfoViewData;
use App\Services\ViewDetails\GridInfoViewData;
use App\Services\SprintService;
use Illuminate\Support\Facades\DB;

class AllRequests extends Component
{
    // use WithServiceMethods;
    use WithViewDetailsView;
    private string $serviceClass = RequestService::class;
    public ChangeRequestForm $form;
    public SprintForm $sprintForm;

    public $taskAlreadyInSprint;
    public $noActiveSprints;
    public $project;
    public $sprints;
    public $currentSprint;

    public array $selectedChangeRequests = [];

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
    public function showAddToSprintForm($items)
    {

        $this->isOpenedNewSprintForm = true;
        $this->selectedChangeRequests = $items;
    }

    private function createTasks($items, $sprint)
    {
        foreach ($items as $requestId) {
            $request = Request::findOrFail($requestId);
            (new TaskService())->loadFieldValues([
                'projectId' => $this->project->id,
                'requestId' => $request->id,
                'sprintId' => $sprint->id,
                'title' => $request->title,
                'body' => $request->request,
                'start' => $sprint->start,
                'end' => $sprint->end,
            ])->create();
        }
    }

    public function createSprintAndTasks()
    {
        $this->sprintForm->validate();
        try {
            $this->sprintForm->validate();
            $sprintDates = $this->sprintForm->all();
            DB::transaction(function () use ($sprintDates) {
                // dd($sprintStart, $sprintEnd);
                $sprint = (new SprintService())->loadFieldValues([
                    'projectId' => $this->project->id,
                    'start' => $sprintDates['start'],
                    'end' => $sprintDates['end']
                ])->create();
                $this->createTasks($this->selectedChangeRequests, $sprint);
            });
            // (new TaskService())->createTasksAndSprintFromRequest($this->project->id, $this->selectedChangeRequests, Carbon::parse($this->sprintForm->start), Carbon::parse($this->sprintForm->end));
            $this->dispatch(
                'displayNotification',
                message: 'Sprint and Tasks has been created and added!',
                type: NotificationType::Information
            )->to(\App\Livewire\Notification::class);

            $this->tableKey = rand();
            $this->isOpenedNewSprintForm = false;
            $this->sprintForm->reset();
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
            $this->createTasks($items, $sprint);
            // (new TaskService())->createTasksForSprint($items, $sprint);
            $this->dispatch(
                'displayNotification',
                message: 'Tasks has been created and added to the last sprint!',
                type: NotificationType::Information
            )->to(\App\Livewire\Notification::class);

            $this->tableKey = rand();
            $this->isOpenedNewSprintForm = false;
            $this->sprintForm->reset();
        } catch (ServiceException $e) {
            $this->dispatch(
                'displayNotification',
                message: $e->getMessage(),
                type: NotificationType::Alert
            )->to(\App\Livewire\Notification::class);
        }
    }

    #[On('Delete')]
    public function deleteRequest($items)
    {
        try {
            (new RequestService())->loadFieldValues(['id' => $items[0]])->delete();
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

    public function createChangeRequestBuilder()
    {
        $builder = new FormBuilder('createChangeRequest()', 'static');
        $builder
            ->addTitle("Create New Change Request")
            ->addInput(new FormTextInput('form.title', 'Request Title', 'text'))
            ->addInput(new FormTextArea('form.request', 'Request Body', 'text'))
            ->addButton(new FormButton('Close', 'closeNewSprintDialog'));
        return $builder;
    }

    public function removeTask()
    {
        $task = Task::where('requestId', $this->showDetails)->first();
        try {
            (new TaskService)->loadFieldValues(['id' => $task->id])->delete();
        } catch (ServiceException $e) {
            $this->dispatch(
                'displayNotification',
                message: $e->getMessage(),
                type: NotificationType::Alert
            )->to(\App\Livewire\Notification::class);
        }
    }

    public function createBuilder()
    {
        $builder = new FormBuilder('createSprintAndTasks()', 'fixed');
        $builder
            ->addTitle("Create New Sprint And Tasks")
            ->addInput(new FormTextInput('sprintForm.start', 'Sprint Start', 'date'))
            ->addInput(new FormTextInput('sprintForm.end', 'Sprint End', 'date'))
            ->addButton(new FormButton('Close', 'closeNewSprintDialog'));
        return $builder;
    }

    public function getDetailsViewBuilder()
    {
        $requestText = Request::findOrFail($this->showDetails);
        $associatedTask = Task::where('requestId', $this->showDetails)->first();
        $associatedTaskLink = is_null($associatedTask) ? "No associated task." : (new HyperLinkCreator('tasks'))->setParams($associatedTask['title'], ['showDetails' => $associatedTask['id']]);

        $generalSectionData = [
            'assocTask' => $associatedTaskLink
        ];

        if (!is_null($associatedTask)) {
            $generalSectionData[] = new ButtonCreator('Delete Task', 'removeTask', 'Are you sure to delete the currrent task?');
            $generalSectionData['status'] = $associatedTask->status;
        }

        return (new DetailsViewBuilder())
            ->addPage('General')
            ->addPage('Info')
            ->addSection(
                'General',
                new GridInfoViewData($generalSectionData, 'Info', ['assocTask' => 'Associated Task'])
            )->addSection('General', new BlockInfoViewData(
                'Request Details',
                $requestText->title,
                $requestText->request
            ))
            ->addSection('General', new CommentsViewData(
                'Comments',
                Request::class,
                $this->showDetails
            ));
    }

    public function closeNewSprintDialog()
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
