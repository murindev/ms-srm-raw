<?php


namespace App\Admin\Controllers\Logistic;


use Encore\Admin\Layout\Content;

class MapController
{
    public function index(Content $content)
    {
        $date = request()->get('date') ? request()->get('date') : date("Y-m-d");
        return $content
            ->header('Index')
            ->description('description')
            ->body('
            <div id="DGMap"><d-g-map 
            case="standAlone" 
            init_date="' .$date. '"
            action="' .request()->get('action'). '"
            act_id="' .request()->get('act_id'). '"
            ></d-g-map></div>
            ');
    }
//<script src="/js/new/map.js"></script>
}
