<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Component;
use App\Enums\NotificationType;
use App\Exceptions\FilterException;
use App\Services\Filter;
use Livewire\Attributes\On;

class TableView extends Component
{
    use WithPagination;

    public ?Filter $filter = null;

    #[Url]
    public string $search = "";

    // #[Url]
    public array $f = [];

    public string $tableClass;

    public array $selectedItems = [];

    public bool $showFilter = false;


    public function mount($tableClass, ?Filter $filter = null)
    {
        if (!is_null($filter)) {
            $this->filter = $filter;
        }
        $this->tableClass = $tableClass;
        $this->filter->filterConditions = $this->f;
        if (!empty($this->f)) $this->filter->step = 1;
    }

    public function clearSearchInput()
    {
        $this->search = "";
    }


    public function updated($propName, $value)
    {
        $this->selectedItems =  array_filter($this->selectedItems, fn ($el) => $el != $value);
        if (explode('.', $propName)[0] == 'selectedItems' && !$this->tableClass::$allowMultipleSelection) {
            $value == '__rm__' ? $this->selectedItems = [] : $this->selectedItems = [$value];
        } elseif (explode('.', $propName)[0] == 'selectedItems' && $this->tableClass::$allowMultipleSelection) {
            $value == '__rm__' ?
                $this->selectedItems =  array_filter($this->selectedItems, fn ($el) => $el != $value) :
                array_push($this->selectedItems, $value);
        }
    }

    public function callFilterMethod($methodName, ...$params)
    {
        try {
            $data =  $this->filter->{$methodName}(...$params);
            if (in_array($methodName, ['addConditions', 'deleteCondition'])) {
                $this->f = $this->filter->filterConditions;
            }
            return $data;
        } catch (FilterException $e) {
            $this->dispatch(
                'displayNotification',
                message: $e->getMessage(),
                type: NotificationType::Alert
            )->to(\App\Livewire\Notification::class);
        }
    }

    public function action($actionName)
    {
        if ($actionName == "Filter") {
            $this->showFilter = !$this->showFilter;
        }elseif (count($this->selectedItems)) {
            $this->dispatch($actionName, $this->selectedItems, $this->tableClass);
        } else {
            $this->dispatch(
                'displayNotification',
                message: 'Please select item(s).',
                type: NotificationType::Alert
            )->to(\App\Livewire\Notification::class);
        }
    }

    protected function queryString()
    {
        return [
            'f' => [
                'as' => !is_null($this->filter) ? $this->filter->f : 'f',
            ],
        ];
    }

    public function render()
    {
        if (is_null($this->filter)) {
            $tableClassInstance = new $this->tableClass;
        } else {
            $tableClassInstance = new $this->tableClass($this->filter);
        }
        return view('livewire.table-view', [
            'data' => $tableClassInstance->render($this->search),
            'tableClassInstance' => $tableClassInstance
        ]);
    }
}
