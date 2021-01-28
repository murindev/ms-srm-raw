<?php

namespace App\Admin\Controllers\Crm;

use App\Http\Controllers\HelperController;
use App\Models\Crm\LidStatus;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class StatisticMainController extends Controller
{
    use HasResourceActions;

    public static function manager(){
        $user = config('const.admin')->user()->toArray();
        return $user['id'];
    }

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    //<script src='/js/new/statistic.lid.js'></script>
    public function index(Content $content)
    {
        $h = json_encode(HelperController::helpers(),JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return $content
            ->header('Index')
            ->description('description')
            ->body("<div id='statisticLid'><crm-statistic :manager='".self::manager()."' :h='".$h."'></crm-statistic></div>");
//            ->body("<div id='statisticLid'><statistic-lid manager='".self::manager()."' :h='".$h."'></statistic-lid></div>");
    }

    public function index2(Content $content)
    { dump('hhhh');
        $h = json_encode(HelperController::helpers(),JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return $content
            ->header('Index')
            ->description('description')
            ->body("<div id='statisticLid'><crm-statistic :manager='".self::manager()."' :h='".$h."'></crm-statistic></div>");
//            ->body("<div id='statisticLid'><statistic-lid manager='".self::manager()."' :h='".$h."'></statistic-lid></div>");
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body();
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body();
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body();
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */

}
