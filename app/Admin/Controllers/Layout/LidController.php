<?php

namespace App\Admin\Controllers\Layout;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
use App\Models\Crm\Act;
use App\Models\Crm\Customer;
use App\Models\Crm\Lid;
use App\Models\Helper\Action;
use App\Models\Helper\Status;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Show;

class LidController extends Controller
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
    public function index(Content $content)
    {
        $h = json_encode(HelperController::arrHelpers(),JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return $content
            ->header('Dashboard')
            ->description('Description...')
            ->body("<div><crm-lid-list :manager='".self::manager()."' :h='".$h."'></crm-lid-list></div>");
//            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body("<div id='lid'><lid></lid></div>");
        /*<script src='/js/new/lid.js'></script>*/
    }


    /**
     * Edit interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {


        return $content
            ->header('Detail')
            ->description('description')
            ->body("<div id='lid'>
                <lid id='".$id."' salt='".rand(100000000,990000000)."' open_act='".request()->get('open_act')."'></lid>
                </div>");
        // <script src='/js/new/lid.js'></script>
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
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Lid);
//        $grid->disableCreateButton();
//        $grid->expandFilter();
        $grid->filter(function ($filter){
            $filter->expand();
            $filter->like('contract', '№ Договора');
            $filter->where(function ($query){
                $query->whereHas('customer', function ($query) {
                    $query->where('phone', 'like', "%{$this->input}%");
                });
            },'Телефон')->mobile(['mask' => '+7 (999) 999 99 99']);

            $filter->where(function ($query){
                $query->whereHas('customer', function ($query) {
                    $query->where('organization', 'like', "%{$this->input}%");
                });
            },'Организация');

            $filter->where(function ($query){
                $query->whereHas('status', function ($query) {
                    $query->where('status', $this->input);
                });
            },'Статус')->select(Status::all()->pluck('name','id'));

            $filter->equal('action','Действие')->select(Action::all()->pluck('name','id'));


        });


        $grid->id('ID')->sortable();
        $grid->manager_starter('manager_starter')->sortable();
        $grid->date_start('Дата обращения')->sortable();
        $grid->contract('Номер договора')->sortable();
//        $grid->servicing('Тип обслуживания')->display(function ($servicing){
//            return Servicing::find($servicing)->name;
//        })->sortable();
        $grid->status('Статус')->display(function ($status){
            if($status['status']){
                return $status['date'].' (' .Status::find($status['status'])->name. ')';
            } else {
                return '';
            }

        });
        $grid->action('Действие')->using(Action::all()->pluck('name','id')->toArray())->sortable();
        $grid->action_date('Дата и время действия')->sortable();

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed   $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Lid::findOrFail($id));

        $show->id('ID');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Lid);

        $form->display('id', 'ID');
        $form->display('created_at', 'Created At');
        $form->display('updated_at', 'Updated At');

        return $form;
    }
}
