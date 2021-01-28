<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;

class SalesFunnelPersonalController extends Controller
{

    use HasResourceActions;

    public static function manager(){
        $user = config('const.admin')->user()->toArray();
        return $user['id'];
    }
    
    public function index(Content $content)
    {

        $h = json_encode(HelperController::arrHelpers(),JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        
        return $content
            ->header('Воронка продаж')
            ->body("<div id='crm-funnel_personal'><crm-funnel_personal :manager='".self::manager()."' :h='".$h."'></crm-funnel_personal></div>")
//            ->description('Description...')
//            ->row(Dashboard::title())
//            ->row(function (Row $row) {
//
//                $row->column(4, function (Column $column) {
//                    $column->append(Dashboard::environment());
//                });
//
//                $row->column(4, function (Column $column) {
//                    $column->append(Dashboard::extensions());
//                });
//
//                $row->column(4, function (Column $column) {
//                    $column->append(Dashboard::dependencies());
//                });
//            })
            ;
    }
}
