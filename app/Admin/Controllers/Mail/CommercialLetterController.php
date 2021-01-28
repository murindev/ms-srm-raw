<?php

namespace App\Admin\Controllers\Mail;

use App\Models\Admin\AdminUser;
use App\Models\Crm\Volume;
use App\Http\Controllers\Controller;
use App\Models\Helper\Company;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class CommercialLetterController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        $user =  json_encode(\Admin::user());
        $managers = \DB::table('admin_role_users')->whereIn('admin_role_users.role_id', [4,2,1])->pluck('user_id')->toArray();
        $arrUsers = json_encode(AdminUser::whereIn('id', $managers)->where('active',1)->get()->keyBy('id')->toArray());
        $sites = json_encode(Company::all()->toArray());
        return $content
            ->header('Index')
            ->description('description')
            ->body("<commercial-letters :user='".$user."' :sites='".$sites."' :managers='".$arrUsers."'/>");
    }
}
