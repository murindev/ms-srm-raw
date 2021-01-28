<?php


namespace App\Admin\Controllers\Logistic;


use App\Http\Controllers\Controller;
use App\Models\Admin\AdminUser;
use App\Models\Calc\MethodPrice;
use App\Models\Crm\Implement;
use App\Models\Helper\Action;
use App\Models\Helper\Company;
use App\Models\Helper\Constant;
use App\Models\Helper\Drug;
use App\Models\Helper\Equipment;
use App\Models\Helper\Method;
use App\Models\Helper\PaymentRule;
use App\Models\Helper\Pest;
use App\Models\Helper\Phone;
use App\Models\Helper\Region;
use App\Models\Helper\Servicing;
use App\Models\Helper\Square;
use App\Models\Helper\Status;
use App\Models\Helper\Tools;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Form;
use Encore\Admin\Layout\Content;

class RouteController extends Controller
{

    public function index(Content $content)
    {
        return $content
            ->header('Index')
            ->description('description')
            ->body('<div id="logisticRoutes"><logistic-routes></logistic-routes></div>');
    }

    public function edit($id, Content $content)
    {


        $activeUser = config('const.admin')->user()->id;
//        dump();
        return $content
            ->header('Edit')
            ->description('description')
            ->body('<div id="logisticRoutesMap"><logistic-routes-map 
                                date="'.$id.'" 
                                user="'.$activeUser.'" 
                                region="' .request()->get('region'). '"
                                
                                ></logistic-routes-map></div>');
    }

}
