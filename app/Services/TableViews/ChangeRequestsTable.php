<?php

namespace App\Services\TableViews;

use App\Services\TableViews\Buttons\AddToNewSprintButton;
use App\Services\TableViews\Buttons\AddToSprintButton;
use App\Services\TableViews\Buttons\DeleteToolbarButton;
use App\Services\TableViews\Buttons\EditToolbarButton;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeRequestsTable implements TableInterface
{
    public static array $headers = ['ID', 'Requester ID', 'Request Title', 'Request Body', 'Creation Date'];

    public static bool $allowMultipleSelection = true;

    public static $dates = ['created_at'];

    public static function getToolbarButtons()
    {
        return [new AddToSprintButton(), new AddToNewSprintButton(), new DeleteToolbarButton()];
    }

    public function render(string $keyword = '')
    {
        // dd(Schema::getColumnType('change_request', 'created_at'));
        $query = DB::table('change_request')
            ->select('id', 'requesterId', 'title', 'request', 'created_at')->orderBy('created_at', 'desc');
        if ($keyword) {
            $query->where('requestId', 'like', "%$keyword%")
                ->orWhere('email', 'like', "%$keyword%")
                ->orWhere('phoneNumber', 'like', "%$keyword%");
        }
        return $query->simplePaginate(15);
    }
}
