<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Component;
use App\Enums\NotificationType;


class TableView extends Component
{
    use WithPagination;

    #[Url]
    public string $search = "";

    public string $tableClass;

    public array $selectedItems = [];


    public function mount($tableClass)
    {
        $this->tableClass = $tableClass;
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

    public function action($actionName)
    {
        if (count($this->selectedItems)) {
            $this->dispatch($actionName, $this->selectedItems, $this->tableClass);
        } else {
            $this->dispatch(
                'displayNotification',
                message: 'Please select item(s).',
                type: NotificationType::Alert
            )->to(\App\Livewire\Notification::class);
        }
    }

    public function render()
    {
        $tableClassInstance = new $this->tableClass;
        return view('livewire.table-view', [
            'data' => $tableClassInstance->render($this->search),
            'tableClassInstance' => $tableClassInstance
        ]);
    }
}
