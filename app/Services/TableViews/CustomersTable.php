<?php

namespace App\Services\TableViews;

use App\Services\HyperLinkCreator;
use App\Services\TableViews\Buttons\DeleteToolbarButton;
use App\Services\TableViews\Buttons\EditToolbarButton;
use Illuminate\Support\Facades\DB;

class CustomersTable extends TableView
{
    public static array $headers = ['ID', 'Full Name', 'Email', 'Phone Number'];

    public static bool $allowMultipleSelection = false;

    public static function getToolbarButtons()
    {
        return [new EditToolbarButton(), new DeleteToolbarButton()];
    }    

    public function render(string $keyword = '')
    {
        $query = DB::table('customers')
            ->select('id', 'fullname', 'email', 'phoneNumber')->orderBy('created_at', 'desc');

        if ($keyword) {
            $query->where('fullname', 'like', "%$keyword%")
                ->orWhere('email', 'like', "%$keyword%")
                ->orWhere('phoneNumber', 'like', "%$keyword%");
        }
        return $query->simplePaginate(15);
    }
}
