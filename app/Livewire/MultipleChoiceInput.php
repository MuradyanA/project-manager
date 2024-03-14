<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\TaskUser;

class MultipleChoiceInput extends Component
{

    public $searchValue = "";
    public $searchResults = [];
    public $assignModel;
    public $searchModel;
    public $searchFields = [];
    public $fieldsToShow = [];
    public $selectedItems = [];

    public function mount($searchModel, $searchFields, $fieldsToShow, $selectedIds = [])
    {
        $this->searchModel = $searchModel;
        $this->searchFields = $searchFields;
        $this->fieldsToShow = $fieldsToShow;
        $this->selectedItems = count($selectedIds) > 0 ? $searchModel::whereIn('id', $selectedIds)->get()->toArray() : [];
        // dd($this->selectedItems);
    }

    public function searchUser()
    {
        $query = $this->searchModel::select(array_merge($this->fieldsToShow, ['id']));
        for ($i = 0; $i < count($this->searchFields); $i++) {
            if ($i == 0) {
                $query->where($this->searchFields[0], 'like', "%{$this->searchValue}%")
                    ->limit(4);
            } else {
                $query->orWhere($this->searchFields[$i], 'like', "%{$this->searchValue}%")
                    ->limit(4);
            }
        }
        $this->searchResults = $query->get()->toArray();
    }

    public function addItemToList($item)
    {
        // if (count(array_filter($this->selectedItems, fn ($key, $value) => $key == 'id' ? $value = $id : false, ARRAY_FILTER_USE_BOTH)) == 0) {
        //     $foundItem = array_filter($this->searchResults, fn ($elem) => $elem['id'] == $id);
        //     // if (!empty($this->selectedItems)) {
        //     foreach ($this->selectedItems as $item) {
        //         if ($item == $id) {
        //             return;
        //         } else {
        //             $this->selectedItems = array_merge($this->selectedItems, $foundItem);
        //         }
        //     }
        // } else {
        //     $this->selectedItems = $foundItem;
        // }

        if (!in_array($item, $this->selectedItems)) {
            $this->selectedItems = array_unique([...$this->selectedItems, $item]);
            $this->dispatch('itemSelected', $item, $this->searchModel);
        }
        // dd(array_search($id, $this->searchResults));
        // }
    }

    public function removeUserFromList($item)
    {
        $this->selectedItems = array_filter($this->selectedItems, fn($value) => $value != $item);
        $this->dispatch('itemRemoved', $item, $this->searchModel);
    }

    public function render()
    {
        return view('livewire.multiple-choice-input');
    }
}
