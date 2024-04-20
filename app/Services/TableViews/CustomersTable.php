<?php

namespace App\Services\TableViews;

use App\Services\HyperLinkCreator;
use App\Services\TableViews\Buttons\DeleteToolbarButton;
use App\Services\TableViews\Buttons\EditToolbarButton;
use Illuminate\Support\Facades\DB;

class CustomersTable extends TableView
{
    public static array $headers = ['ID', 'Full Name', 'Email', 'Phone Number', 'Created At'];

    protected static array $toolbarButtons = [];

    public static bool $allowMultipleSelection = false;

    public static function getToolbarButtons()
    {
        return array_merge([new EditToolbarButton(), new DeleteToolbarButton()], static::$toolbarButtons);
    }

    public function render(string $keyword = '')
    {
        // $query = DB::table('customers')
        //     ->select('id', 'fullname', 'email', 'phoneNumber', 'created_at')->orderBy('created_at', 'desc');
        $query = DB::table('customers')
            ->select('id', 'fullname', 'email', 'phoneNumber', DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d %H:%i') AS formatted_created_at"))
            ->orderBy('created_at', 'desc');
        $this->filter->applyFilter($query);
        if ($keyword) {
            $query->where('fullname', 'like', "%$keyword%")
                ->orWhere('email', 'like', "%$keyword%")
                ->orWhere('phoneNumber', 'like', "%$keyword%");
        }
        return $query->simplePaginate(15);
    }
}
