<?php

namespace App\Admin\Controllers\Helper;

use App\Models\Helper\Rules;
use App\Http\Controllers\Controller;
use App\Models\Helper\Subject;
use Encore\Admin\Auth\Database\Role;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class RulesController extends Controller
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
        $grid = new Grid(new Rules);

        $grid->id('ID');
        $grid->role('Пользователь')->select(Role::all()->pluck('name','id'));
        $grid->control('Контролер')->select(Role::all()->pluck('name','id'));
        $grid->name('Правило')->editable();
        $grid->name_short('Правило кратко')->editable();
        $grid->strict('Обязательное?')->switch();
        $grid->value('Значение')->editable();
        $grid->subject('Зона контроля')->select(Subject::all()->pluck('name','id'));
        $grid->start_end('Начало периода?')->switch();
        $grid->sort('Сортировка')->number()->editable();
        $grid->gap('Лимит дейсвия(мин)')->number()->editable();
        $grid->penalty('Штраф')->number()->editable();
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
        $show = new Show(Rules::findOrFail($id));

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
        $form = new Form(new Rules);

        $form->display('ID');
        $form->select('role','Пользователь')->options(Role::all()->pluck('name','id'));
        $form->select('control','Контролер')->options(Role::all()->pluck('name','id'));

        $form->text('name','Правило');
        $form->text('name_short','Правило кратко');
        $form->switch('strict','Обязательное?');
        $form->text('value','Значение');
        $form->select('subject','Зона контроля')->options(Subject::all()->pluck('name','id'));
        $form->switch('start_end','Начало периода?');
        $form->number('sort','Сортировка');
        $form->number('gap','Лимит дейсвия(мин)');
        $form->number('penalty','Штраф');
        return $form;
    }
}
