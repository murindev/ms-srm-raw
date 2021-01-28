<?php

namespace App\Admin\Controllers\Helper;

use App\Models\Helper\Company;
use App\Models\Helper\Phone;
use App\Http\Controllers\Controller;
use App\Models\Helper\Region;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class PhoneController extends Controller
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
        $grid = new Grid(new Phone);

        $grid->id('ID');
//        $grid->region('Регион');
        $grid->region('Регион')->display(function ($region){
            return Region::where('id',$region)->first()->region;
        });
        $grid->phone('Телефон');
        $grid->owner('Владелец')->display(function ($owner){
            return Company::where('id',$owner)->first()->name;
        });
        $grid->hint('Назначение');
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
        $show = new Show(Phone::findOrFail($id));

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
        $form = new Form(new Phone);

        $form->display('id');
        $form->select('region','Регион')->options(Region::pluck('region','id'));
        $form->select('owner','Владелец')->options(Company::pluck('name','id'));
        $form->text('hint','Назначение');
        $form->text('phone','Телефон');

        return $form;
    }
}
