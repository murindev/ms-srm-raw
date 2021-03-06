<?php

namespace App\Admin\Controllers\Vue;


use App\Admin\Extensions\Form\NestedFormFlat;
use App\Models\Admin\AdminUser;
use App\Models\Calc\MethodPrice;
use App\Models\Crm\Act;
use App\Models\Crm\Customer;
use App\Models\Crm\Implement;
use App\Models\Crm\Lid;
use App\Http\Controllers\Controller;
use App\Models\Crm\LidAction;
use App\Models\Crm\LidStatus;
use App\Models\Crm\Volume;
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
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Show;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/*
use App\Models\Crm\Volume;
use App\Models\My\Test;
use Encore\Admin\Form\NestedForm;
use App\Models\Ajax\Modal;
use App\Models\Crm\MasterVolume;
use App\User;
use function Composer\Autoload\includeFile;
use Encore\Admin\Admin;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\Permission;
use Encore\Admin\Layout\Row;
use Encore\Admin\Facades\Admin as nb;
use Encore\Admin\Widgets\Table;
*/

class LidController extends Controller
{
    use HasResourceActions;

    public $modelData;

    private $edit = false;

    private $actHolder = [];

    public $current;
    public $customer;

    /*
     *   +"year": 2019
  +"month": 5
  +"day": 30
  +"dayOfWeek": 4
  +"dayOfYear": 150
  +"hour": 0
  +"minute": 0
  +"second": 0
  +"micro": 0
  +"timestamp": 1559174400
  +"formatted": "2019-05-30 00:00:00"
     * */

    public function contract($type = false)
    {
        $st = Carbon::today()->toObject();
        $oLid = Lid::whereBetween('date_start', [Carbon::make($st->formatted), Carbon::make($st->formatted)->add(1, 'day')])
                ->get()->count() + 1;

        return str_replace('20', '', $st->year) . '-' . $st->month . '-' . $oLid . '-' . $st->day;


        /*
        if ($type)
            return str_replace('20', '', $st->year) . '-' . $st->month . '-' . $oLid . '-' . $st->day . '-U';
        else
            return str_replace('20', '', $st->year) . '-' . $st->month . '-' . $oLid . '-' . $st->day;*/
    }

    public function modelData(){
        return $this->modelData = Lid::where('id',request()->segment(3))->first();
    }

    public function usersList(){
        $arrUsers = AdminUser::all()->keyBy('id')->toArray();
        $arrUsers[0] = [
            'active' => 1,
            'avatar' => null,
            'city' => 495,
            'color' => '#ff0000',
            'name' => '????????????'
        ];
        return $arrUsers;
    }

    public function helpers(){
        $helpers = [];

        $helpers['user'] = $this->usersList();
        $helpers['masters'] = Administrator::whereHas('roles', function ($q) {
            $q->where('role_id', 3); // ->where('active', 1)
        })->get()->keyBy('id');
        $helpers['actions'] = Action::all()->keyBy('id')->toArray();
        $helpers['companies'] =  Company::all()->keyBy('id')->toArray();
        $helpers['drugs'] = Drug::all()->keyBy('id')->toArray();
        $helpers['equipments'] = Equipment::all()->keyBy('id')->toArray();
        $helpers['methods'] = Method::all()->keyBy('id')->toArray();
        $helpers['pests'] = Pest::all()->keyBy('id')->toArray();
        $helpers['phones'] = Phone::all()->keyBy('id')->toArray();
        $helpers['regions'] = Region::all()->keyBy('id')->toArray();
        $helpers['servicing'] = Servicing::all()->keyBy('id')->toArray();
        $helpers['square'] = Square::all()->keyBy('id')->toArray();
        $helpers['statuses'] = Status::all()->keyBy('id')->toArray();
        $helpers['tools'] = Tools::all()->keyBy('id')->toArray();
        $helpers['payment_rules'] = PaymentRule::all()->keyBy('id')->toArray();
        $helpers['constants'] = Constant::all()->keyBy('id')->toArray();
        $helpers['methods_prices'] = MethodPrice::all()->groupBy('pest')->toArray();
        return $helpers;
    }


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
        return $content
            ->header('Index')
            ->description('description')
            ->body("<div id='lidsList'><lids-list manager='".$this->manager()."'></lids-list></div><script src='/js/new/lids.list.js'></script>");
//            ->body($this->grid())
//            ;
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

        $l = Lid::with(['statuses','actions','acts'])->where('id',$id)->first()->toArray();
        $lid =  json_encode($l);
        $acts =  json_encode(Act::with(['volume','implement'])->where('parent',$id)->get()->keyBy('id')->toArray());
        $user = json_encode(config('const.admin')->user()->toArray());
        $helpers = json_encode($this->helpers(),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        $statuses = json_encode(LidStatus::where('lid_id',$id)->orderBy('id')->get()->keyBy('id')->toArray());
        $regions = json_encode(Region::all()->keyBy('region')->toArray());

        if($l['customer']){
            $customer = Customer::where('id',$l['customer'])->first()->toArray();
        } else {
            $customer = [
                'name' => '',
                'icon' => '',
                'status' => 0,
                'organization' => '',
                'phone' => '',
                'phone_ext' => '',
                'address' => '',
                'addresses' => '',
                'email' => '',
                'emails' => '',
                'destination' => '',
                'city' => '',
                'region' => '',
                'lat' => '',
                'lon' => ''
            ];
        }
        return $content
            ->header('Edit')
            ->description('description')
            ->body(
//                "<div id='lid'><lid
//                                        v-bind:lid='" .$lid. "'
//                                        v-bind:acts='" .$acts. "'
//                                        v-bind:user='" .$user. "'
//                                        v-bind:helpers='" .$helpers. "'
//                                        v-bind:statuses='" .$statuses. "'
//                                        v-bind:regions='" .$regions. "'
//                                        v-bind:customer='" .json_encode($customer). "'
//                                    ></lid></div>"
            );
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
            ->header('?????????? ??????????')
//            ->description('description')
            ->body($this->formCreate());
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
            $filter->like('contract', '??? ????????????????');
            $filter->where(function ($query){
                $query->whereHas('customer', function ($query) {
                    $query->where('phone', 'like', "%{$this->input}%");
                });
            },'??????????????')->mobile(['mask' => '+7 (999) 999 99 99']);

            $filter->where(function ($query){
                $query->whereHas('customer', function ($query) {
                    $query->where('organization', 'like', "%{$this->input}%");
                });
            },'??????????????????????');

            $filter->where(function ($query){
                $query->whereHas('status', function ($query) {
                    $query->where('status', $this->input);
                });
            },'????????????')->select(Status::all()->pluck('name','id'));

            $filter->equal('action','????????????????')->select(Action::all()->pluck('name','id'));


        });


        $grid->id('ID')->sortable();
        $grid->manager_starter('manager_starter')->sortable();
        $grid->date_start('???????? ??????????????????')->sortable();
        $grid->contract('?????????? ????????????????')->sortable();
//        $grid->servicing('?????? ????????????????????????')->display(function ($servicing){
//            return Servicing::find($servicing)->name;
//        })->sortable();
        $grid->status('????????????')->display(function ($status){
            if($status['status']){
                return $status['date'].' (' .Status::find($status['status'])->name. ')';
            } else {
                return '';
            }
        });
        $grid->action('????????????????')->using(Action::all()->pluck('name','id')->toArray())->sortable();
        $grid->action_date('???????? ?? ?????????? ????????????????')->sortable();

        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableEdit();
            $actions->disableView();
            // prepend an action.
            $actions->prepend('<a href="/layout/lid/' .$actions->getKey('id'). '/edit"><i class="fa fa-paper-plane"> ??????????????????????????</i></a>');
        });

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

        $this->modelData();

        if(request()->segment(4)){
            $this->edit = true;

            $this->customer = Customer::where('lid_id',request()->segment(3))->first();

            $this->current = Lid::find(request()->segment(3))->first();

        }
        // presets
//        $form->disableEditingCheck();
        $form->disableCreatingCheck();
        $form->disableViewCheck();
        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableView();
            $tools->disableList();
        });


// ???????????????? ????????????:

        $form->tab('???????????????? ????????????',function (Form $form){



            if($this->modelData->manager_starter){
                $form->htmlField('manager_starter','???????????????? ???????????????? ??????')
                    ->display(AdminUser::where('id',$this->modelData->manager_starter)->first()->name);
            }

            // ???????? ??????????????????:
            $form->datetime('date_start', '???????? ??????????????????:');

            // ???????????? ????????:
            // ?? (????????):

            $form->select('site','????????')->options(Company::all()->pluck('name','id'));

            $form->select('status.status', ' ???????????? ????????:')->options(Status::all()->pluck('name', 'id'))->default(4);
            $form->datetime('status.date', '?? (????????):')->default(Carbon::now()->toDateTimeString());

            //????????????????:

            $form->htmlField('manager','????????????????')
                ->display(AdminUser::where('id',$this->modelData->manager)->first()->name);
            $form->hidden('manager')->value(config('const.admin')->user()->id);


            //?????? ????????????????????????: ????????????????:


            $form->select('servicing', '?????? ????????????????????????:')->options(Servicing::all()->pluck('name', 'id'))->default(1);
            $form->select('action', '????????????????:')->options(Action::all()->pluck('name', 'id'))->required();
            $form->datetime('action_date', '???????? ?? ?????????? ????????????????:');
            $form->text('action_note', '????????????????????:');            //??????????????????????:
            $form->textarea('comment','??????????????????????:');
        });

        $form->tab('???????????? ??????????????',function (Form $form){
            $states = [
                'on' => ['value' => 1, 'text' => '??????????????????????', 'color' => 'danger'],
                'off' => ['value' => 0, 'text' => '????????????????????????', 'color' => 'success'],
            ];
            $form->switch('customer.status', '???????????? ??????????????:')->states($states);

            $form->text('customer.name', '??????');
            $form->text('customer.organization', '??????????????????????');
            $form->mobile('customer.phone', '??????????????')->options(['mask' => '+7 (999) 999 99 99']);
            $form->mobile('customer.phone_ext', '?????????????? ????????????????????')->options(['mask' => '+7 (999) 999 99 99']);
            $form->email('customer.email', 'email');
            $form->dadataAddress('customer.address', '??????????');
            $form->text('customer.destination', '????????????????????');
            $form->hidden('customer.city');
            $form->hidden('customer.region');
            $form->text('customer.geo_lat','????????????');
            $form->text('customer.geo_lon','??????????????');
//            $form->hidden('center_lat');
//            $form->hidden('center_lon');
        });


        // ?????????? ??????????:
        $form->tab('?????????? ??????????',function (Form $form){

            $form->html();

            $form->hasManyFlat('act', '??????', function (Form\NestedForm $form) {

                $finished = [
                    'on' => ['value' => 1, 'text' => '????', 'color' => 'success'],
                    'off' => ['value' => 0, 'text' => '??????', 'color' => 'danger'],
                ];

                $form->text('act_nr','?????????? ????????');
                $form->switch('floating','???????????????????');
                $form->date('floating_date_from','???????? ??...');
                $form->date('floating_date_to','???????? ????...');
                $form->dadataLatLon('address', '??????????');
                $form->text('lat', '????????????');
                $form->text('lon', '??????????????');
                $form->select('region', '????????????')->options(Region::all()->pluck('region','region'));
                $form->switch('finished','???????????? ???????????????????')->states($finished);
                $form->switch('booking_act_transferred','?????????????????????????? ?????? ???????????????')->states($finished);
                $form->switch('booking_act_signed','?????????????????????????? ?????? ?????????????????')->states($finished);
                $form->file('booking_act_file','?????????????????????????? ?????? ????????');
                $form->switch('implement_act_signed','?????????????????????????????? ?????? ?????????????????')->states($finished);
                $form->file('implement_act_file','?????????????????????????????? ?????? ????????');

                $form->tableFlat('volume','??????????(??)', function ($table) {
                    $table->select('pest','?????????????? ??????????:')->options(Pest::all()->pluck('name','id'))->load('method', '/api/getter/methods');
                    $table->select('method','??????????:')->options(Method::all()->pluck('name','id'));
                    $table->text('square','??????????????:')->pattern('^[0-9]*[.]?[0-9]+$');
                    $table->select('entity','?????????????? ??????????????:')->options(Square::all()->pluck('name','id'));
                    $table->text('price_standard','???????? ????????');
                    $table->text('price_fact','???????? ????????.');
                })->parent('act');

                $form->tableFlat('implement','??????????????????????', function ($table) {
                    $table->dateButton('choose_master','????????');
                    $table->select('master','????????????')->options(Implement::masters());
                    $table->datetime('start_date','???????????? ??????????');
                    $table->datetime('end_date','?????????????????? ??????????');
                })->parent('act');

            });
        });

        $form->tab('??????????????????',function (Form $form){
            $form->text('contract', '?????????? ????????????????:');
            $form->switch('contract_transferred', '?????????????? ??????????????:');
            $form->switch('contract_signed', '?????????????? ????????????????:');
            $form->file('contract_file', '???????? ????????????????:');
            $form->switch('customer_payment','?????????????');
            $form->select('payment_rule','?????????????? ?????????????????')->options(PaymentRule::all()->pluck('name','id'));
            $form->text('payment_condition','?????????????? ???????????????????? (?? ????????)');
        });

        $form->hasMany('volumes','??????????(??)', function (Form\NestedForm $form) {
            $form->select('pest','?????????????? ??????????:')->options(Pest::all()->pluck('name','id'))->load('method', '/api/getter/methods');
            $form->select('method','??????????:')->options(Method::all()->pluck('name','id'));
            $form->number('square','??????????????:')->default(0);
            $form->select('entity','?????????????? ??????????????:')->options(Square::all()->pluck('name','id'));
            $form->text('price_standard','???????? ????????');
            $form->text('price_fact','???????? ????????.');
            $form->text('lid_id');
            $form->text('parent');
            $form->text('id');
        });
        $form->hasMany('implements','??????????????????????', function (Form\NestedForm $form) {
            $form->text('master','????????????');
            $form->datetime('start_date','???????????? ??????????');
            $form->datetime('end_date','?????????????????? ??????????');
            $form->text('lid_id');
            $form->text('parent');
            $form->text('id');
        });


        $form->submitted(function (Form $form){
            $form->ignore('center_lat');
            $form->ignore('center_lon');
        });
        $form->saving(function (Form $form) {

            if($form->act){





                $form->volumes = [];
                $form->implements = [];
                $newAct = [];
                $relations = [];
                /* acts array*/
                foreach ($form->act as $actKey => $actArr) if(($actArr['act_nr'])) { // 1

                    /*act with relations*/

                    $currentActKey = is_numeric($actKey) ? $actKey : Act::max('id') + 1;

                    foreach ($actArr as $actSubKeys => $actVal) { // 2

                        /*relations*/
                        if (is_array($actVal)) {
                            $relationName = $actSubKeys.'s';
                            foreach ($actVal as $relKey => $relVal) { // 3
                                if(is_numeric($relKey)) {
                                    $relVal['id'] = $relKey;
                                    $relationID = $relKey;

                                } else {
                                    $relVal['id'] = false;
                                    $relationID = 'new_'.rand(1,1000);
                                }
                                $relVal['parent'] = $currentActKey;
                                $relVal['lid_id'] = $form->model()->id;
                                $relations[$relationName][$relationID] = $relVal;
                            }

                        } else {
                            /*act*/
                            $newAct[$actKey][$actSubKeys] = $actVal;
                        }
                    }
                }

                /*adding to form data*/
                $form->act = $newAct;
                foreach ($relations as $relationName =>$relationValue) {
                    $form->$relationName = $relationValue;
                }
            }
        });








        return $form;
    }

    public function formCreate(){
        $form = new Lid;
        $form->contract = $this->contract();
        $form->date_start = Carbon::now()->toDateTimeString();
        $form->manager = config('const.admin')->user()->id;
        $form->manager_starter = config('const.admin')->user()->id;



        $form->save();
        $lastID = DB::getPdo()->lastInsertId();

//        return redirect('/vue/lid/'.$lastID.'/edit');
        return redirect('/layout/lid/'.$lastID.'/edit');

        /*

                $form->text('contract', '?????????? ????????????????:')->value($this->contract());
                $form->html("
                <script>
        //        $(document).ready(function(){
        //            $('.btn-primary').click();
        //        })

        </script>
                ");
                $form->saved(function (Form $form){

        //            dump($form->model());
        //            exit();
        //            return redirect('/crm/lids/'.$form->model()->id.'/edit');
                });
                return $form;*/

//                    ($form->customer_status == 'on') ? $contract = $this->contract(true) : $contract = $this->contract();
//            $form->contract = value($contract);

    }



    public function updateField(){
        $request = request()->all();
        if($request['value'] == 'true'){
            $request['value'] = 1;
        } elseif ($request['value'] == 'false') {
            $request['value'] = 0;
        } elseif ($request['value'] == 'null') {
            $request['value'] = null;
        }
        switch ($request['model']):
            case 'lid':


                $update = Lid::where('id',(int)$request['id'])->update([$request['field'] => $request['value']]);
                return $update;
                break;
            case 'action':
                $update = Lid::where('id',(int)$request['id'])->update([$request['field'] => $request['value']]);
                $find = LidAction::where('salt',$request['salt'])->first();
                if(is_null($find)) {
                    $arr = [
                        'lid_id' => $request['id'],
                        'manager' => $request['manager'],
                        'salt' => $request['salt'],
                        $request['field'] => $request['value'],
                    ];
                    $res = LidAction::create($arr)->id;
                } else {
                    $res = LidAction::where('id',$find->id)->update([$request['field'] => $request['value']]);
                }
                return $res;
                break;
            case 'customer':

//                $find = Customer::where('id',$request['child_id'])->first();
                $find = Lid::where('id',$request['id'])->first()->customer;




                if(is_null($find)) {
                    $arr = [
                        'lid_id' => $request['id'],
                        $request['field'] => $request['value'],
                    ];
                    $newCustomer = Customer::create($arr)->id;

                    $res = Lid::where('id',$request['id'])->update(['customer' => $newCustomer]);



                } else {
                    $res = Customer::where('id',$find)->update([$request['field'] => $request['value']]);
                }
                return $res;
                break;
            case 'act':
                return Act::where('id',(int)$request['child_id'])->update([$request['field'] => $request['value']]);

                break;
            case 'imp':
                return 'implement';
                break;
        endswitch;
    }

    public function log(){
        $request = request()->all();
        $arr = [
            'lid_id' => $request['id'],
            'manager' => $request['manager'],
            'salt' => $request['salt'],
        ];
        switch ($request['model']):
            case 'status':

                $find = LidStatus::where('salt',$request['salt'])->first();
                $arr['status'] = $request['value'];
                is_null($find) ? $res =  LidStatus::create($arr)->id : $res = LidStatus::where('id',$find->id)->update($arr);
                return $res;

                break;

            case 'act':
                return 'act';
                break;
            case 'vol':
                return 'volume';
                break;
            case 'imp':
                return 'implement';
                break;
        endswitch;
    }

    public function getActRelations(){
        $volumes = Volume::where('parent',request()->get('act'))->get()->toArray();
        $implements = Implement::where('parent',request()->get('act'))->get()->toArray();
        $ArrImplements = [];
        foreach ($implements as $k => $implement){
            $ArrImplements[$k] = $implement;
            $start_date=date_create($implement['start_date']);
            $end_date=date_create($implement['end_date']);
            $ArrImplements[$k]['start_date'] = date_format($start_date,'Y-m-d\TH:i');
            $ArrImplements[$k]['end_date'] = date_format($end_date,'Y-m-d\TH:i');
        }
        return json_encode(array('volumes' => $volumes,'implements' => $ArrImplements));

    }
    public function addVolume(){
        $volume = new Volume;
        $volume->lid_id = request()->get('lid');
        $volume->parent = request()->get('act');
        $volume->save();
        return json_encode(Volume::find($volume->id)->toArray());
    }
    public function addImplement(){
        $implement = new Implement;
        $implement->lid_id = request()->get('lid');
        $implement->parent = request()->get('act');
        $implement->save();
        return json_encode(Volume::find($implement->id)->toArray());
    }

    public function deleteVolume(){
        $volume = Volume::where('id',request()->get('id'))->delete();
        return $volume;

    }
    public function deleteImplement(){
        $implement = Implement::where('id',request()->get('id'))->delete();
        return $implement;
    }

    public function addActRelation(){
        $model = request()->get('model');
        switch ($model){
            case 'volume':
                return Volume::where('id',request()->get('id'))->update([request()->get('field') => request()->get('value')]);
                break;
            case 'implement':
                return Implement::where('id',request()->get('id'))->update([request()->get('field') => request()->get('value')]);
                break;
        }

    }

    public function actPerDate(){
        $arr = json_decode(request()->get('arr'));
//        dump($arr);
        $withVol = [];

        foreach ($arr as $i){
            foreach ($i->periods as $pd){
                $pd->vol = $i->volumes;
                $withVol[] = $pd;
            }
        }
        $tDates = [];

        foreach ($withVol as $period){
            $tDates[$period->floating_date_from . '::' .$period->floating_date_to. '::' .$period->prefer_time_from. '::' .$period->prefer_time_to][] = $period->vol;
        }
//        dump($tDates);
        $pre = [];
        foreach ($tDates as $key => $tDate){
            foreach ($tDate as $arV0l){

                foreach ($arV0l as $r){

                    $pre[$key][] = $r;
                }
            }
        }

        $last = [];
        foreach ($pre as $k => $y){

            $last[$k] = array_unique($y, SORT_REGULAR) ;

        }
        ksort($last);
        $res = [];
        foreach ($last as $dateKey => $sub){
            foreach ($sub as $method){
                if(array_key_exists($dateKey,$res) && array_key_exists($method->pest,$res[$dateKey])){
                    $method->method = array_unique(array_merge($method->method,$res[$dateKey][$method->pest]->method));
                    $method->price_fact = (int)$method->price_fact + (int)$res[$dateKey][$method->pest]->price_fact;
                }
                $res[$dateKey][$method->pest] = $method;
            }
        }


        return json_encode($res);
    }

    public function saveFloatActsSAVE(){
        $arr = json_decode(request()->get('acts'));


        $test =[];
        foreach ($arr as $actArr){
            $volumesArr = [];
            $volumesArrN = [];
            foreach ($actArr->volumes as $k => $v){
                $a = [];
                if(key_exists($v->pest,$volumesArr)){
//                    dump($v->pest);
                    /*                    $volumesArrN[$v->pest] = [
                                            'entity' => $volumesArrN[$v->pest]['entity'],
                                            'kpi' => $volumesArrN[$v->pest]['kpi'],
                                            'lid_id' => $volumesArrN['lid_id'],
                                            'method' => array_merge($volumesArrN[$v->pest]['method'],$v['method']),
                                            'note' => $volumesArrN[$v->pest]['note'],
                                            'parent' => $volumesArrN[$v->pest]['parent'],
                                            'pest' => $volumesArrN[$v->pest]['pest'],
                                            'price_fact' => (int)$volumesArrN[$v->pest]['price_fact'] + (int)$v['price_fact'],
                                            'price_standard' => $volumesArrN[$v->pest]['price_standard'],
                                            'square' => $volumesArrN[$v->pest]['square'],
                                        ];*/
                    $volumesArr[$v->pest]->method = array_unique(array_merge($volumesArr[$v->pest]->method,$v->method));
                    $volumesArr[$v->pest]->price_fact = (int)$volumesArr[$v->pest]->price_fact + (int)$v->price_fact;

                } else {
                    $volumesArr[$v->pest] = $v;
                }
//                $volumesArrN[$v->pest][] = $v;
            }
            unset($actArr->volumes);

//            dump($volumesArr,$volumesArrN,$actArr,'----------');
//            exit();

            $act = Act::create((array)$actArr)->id;
            $test[] = $act;
            foreach ($volumesArr as $vol){
                $vol->parent = $act;
                $vol->method = implode(',',$vol->method);

                $volume = Volume::create((array)$vol)->id;
                $test[] = $volume;
            }
        }

        return array_product($test)/10000000000000000;


    }

    public function saveFloatActs(){
        $arr = json_decode(request()->get('acts'));



        $test =[];
        foreach ($arr as $actArr){
            $volumesArr = [];
            foreach ($actArr->volumes as $k => $v){
                $volumesArr[] = $v;
            }
            unset($actArr->volumes);

            $act = Act::create((array)$actArr)->id;
            $test[] = $act;
            foreach ($volumesArr as $vol){
                $md = [];
                foreach ($vol->method as $m){
                    $md[] = $m;
                }
                $vol->parent = $act;


                $vol->method = implode(',',$md);

                $volume = Volume::create((array)$vol)->id;
                $test[] = $volume;
            }
        }

        return array_product($test)/10000000000000000;


    }

    public function getActs(){
        $acts = Act::where('parent',request()->get('lid'))->get()->toArray();
        return json_encode($acts);
    }
    public function deleteAct(){
        $volumes = Volume::where('parent',request()->get('id'))->delete();
        $implements = Implement::where('parent',request()->get('id'))->delete();
        $act = Act::where('id',request()->get('id'))->delete();

        $volumesTest = Volume::where('parent',request()->get('id'))->get()->toArray();
        $implementsTest = Implement::where('parent',request()->get('id'))->get()->toArray();
        $actTest = Act::where('id',request()->get('id'))->get()->toArray();

        dump(count($volumesTest),count($implementsTest),count($actTest));


        $res = count($volumesTest)+count($implementsTest)+count($actTest);



        return (int)$res;

    }

    public function customerSearch(){
        $customer = Customer::where(request()->get('field'),request()->get('value'))->get()->toArray();
        return json_encode(request()->get('field'));
    }
    public function addEmptyAct(){
        $newAct = new Act;
        $newAct->act_nr = (int)request()->get('cnt') + 1;
        $newAct->parent = (int)request()->get('lid');
        $newAct->save();

        $act = Act::where('parent', request()->get('lid'))->orderBy('id', 'DESC')->get()->toArray();
        return json_encode($act);

    }

    function getVolumes(){
        return Volume::whereIn('parent',explode(',',request()->get('acts')))->get()->toArray();
    }


}
