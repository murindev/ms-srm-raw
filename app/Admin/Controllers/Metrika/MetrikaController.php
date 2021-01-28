<?php

namespace App\Admin\Controllers\Metrika;

use App\Models\Helper\Measure;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class MetrikaController extends Controller
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
        return $content
            ->header('Index')
            ->description('description')
            ->body("<div id='metrika'><metrika></metrika></div><script src='js/new/metrika.js'></script>");
    }
}
