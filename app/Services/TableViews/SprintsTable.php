<?php

namespace App\Services\TableViews;

use App\Services\HyperLinkCreator;
use App\Services\TableViews\Buttons\AddToNewSprintButton;
use App\Services\TableViews\Buttons\AddToSprintButton;
use App\Services\TableViews\Buttons\DeleteToolbarButton;
use App\Services\TableViews\Buttons\EditToolbarButton;
use App\Services\TableViews\Buttons\ViewDetailsButton;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SprintsTable extends TableView
{
    public static array $headers = ['Sprint ID', 'Sprint Number', 'Start', 'End', 'Status', 'Tasks Count'];

    // protected array $hiddenFields = ['customerId'];

    public static bool $allowMultipleSelection = false;

    public static $dates = ['start', 'end'];

    public function __construct()
    {

        $this->addLink('SprintNumber', (new HyperLinkCreator('tasks')));
    }

    public static function getToolbarButtons()
    {
        return [new DeleteToolbarButton()];
    }

    public function setLinkParams($row, $fieldName, $title)
    {
        $this->getLinkMaker($fieldName)->setParams($title, ['sprintId' => $row->id]);
        return $this->getLinkMaker($fieldName);
    }

    public function render(string $keyword = '')
    {
        $query = DB::table('sprintsView');
        if ($keyword) {
            $query->where('Status', 'like', "%$keyword%");
        }
        return $query->simplePaginate(15);
    }
}
