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

class ChangeRequestsTable extends TableView
{
    public static array $headers = ['ID', 'Customer Name', 'Request Title', 'Request Body', 'Creation Date'];

    protected array $hiddenFields = ['customerId'];

    public static bool $allowMultipleSelection = true;

    public static $dates = ['created_at'];

    public function __construct()
    {

        $this->addLink('fullName', (new HyperLinkCreator('customers')));
    }

    public static function getToolbarButtons()
    {
        return [new AddToSprintButton(), new AddToNewSprintButton(), new ViewDetailsButton(), new DeleteToolbarButton()];
    }

    public function setLinkParams($row, $fieldName, $title)
    {
        $this->getLinkMaker($fieldName)->setParams($title, ['showDetails' => $row->customerId]);
        return $this->getLinkMaker($fieldName);
    }

    public function render(string $keyword = '')
    {
        // dd(Schema::getColumnType('change_request', 'created_at'));
        $query = DB::table('change_request')
            ->select('change_request.id', 'customers.fullName', DB::raw('customers.id as customerId'), 'change_request.title', 'change_request.request', 'change_request.created_at')
            ->leftJoin('customers', 'change_request.requesterId', '=', 'customers.id')
            ->orderBy('change_request.created_at', 'desc');
        if ($keyword) {
            $query->where('requestId', 'like', "%$keyword%")
                ->orWhere('email', 'like', "%$keyword%")
                ->orWhere('phoneNumber', 'like', "%$keyword%");
        }
        return $query->simplePaginate(15);
    }
}
