<?php

namespace App\Admin\Controllers\Store;

use App\Models\Helper\Measure;
use App\Models\Store\Chemical;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class ChemicalController extends Controller
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
            ->body($this->grid());
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
            ->body($this->detail($id));
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
            ->body($this->form()->edit($id));
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
        $grid = new Grid(new Chemical);

        $grid->id('ID');
        $grid->name('Название')->editable();
        $grid->measure('Мера измерения')->select(Measure::all()->pluck('name','id'));
       // $grid->expenses('Расход на 1л')->editable();
        $grid->price_net('Закупочная стоимость препарата (1л/кг)')->editable();
//        $grid->expenses_meter('расход на М² (мл/гр)')->editable();

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Chemical::findOrFail($id));

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
        $form = new Form(new Chemical);

        $form->display('id');
        $form->text('name','Препарат');
        $form->select('measure','Мера измерения')->options(Measure::all()->pluck('name','id'));

        $form->text('price_net','Закупочная стоимость препарата (1л/кг)');
       // $form->text('expenses_meter','расход на М² (мл/гр)');
//        $form->text('expenses','Расход на 1л');
//        $form->text('price_extra','price_extra');

        return $form;
    }
}
