<?php


namespace App\Http\QB;


use App\Http\Controllers\Controller;
use App\Models\Crm\Lid;
use App\Models\Crm\LidStatistic;
use Carbon\Carbon;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class LidController extends Controller
{
    private $relations = ['client','act'];
    public $model;

    public function row($value){

        $this->model->where('id', '=', $value);
    }

    public function contract($value){
        $this->model->where(function ($query) use ($value){
            $query->where('contract','like','%'.$value.'%');
        });
    }

    public function site($value){
        $this->model->where(function ($query) use ($value) {
            $query->where('site',$value);
        });
    }
    public function manager_starter($value){
        $this->model->where(function ($query) use ($value) {
            $query->where('manager_starter',$value);
        });
    }
    public function servicing($value){
        $this->model->where(function ($query) use ($value) {
            $query->where('servicing',$value);
        });
    }
    public function customer_name($value){
        $this->model->where(function ($query) use ($value) {
            $query->whereHas('client', function ($q) use ($value){
                $q->where('name','like','%'.$value.'%');
            });
        });
    }
    public function customer_phone($value){
        $this->model->where(function ($query) use ($value) {
            $query->whereHas('client', function ($q) use ($value){
                $q->where('phone','like','%'.$value.'%');
            });
        });
    }
    public function customer_status($value){
        $this->model->where(function ($query) use ($value) {
            $query->whereHas('client', function ($q) use ($value){
                $q->where('status','=',$value);
            });
        });
    }
    public function customer_organization($value){
        $this->model->where(function ($query) use ($value) {
            $query->whereHas('client', function ($q) use ($value){
                $q->where('organization','like','%'.$value.'%');
            });
        });
    }
    public function balance($value){
        $this->model->where(function ($query) use ($value) {
            $query->where('balance',$value < 0 ? '<=' : '>=',$value);
        });
    }
    public function customer_payment($value){
        $this->model->where(function ($query) use ($value) {
            $query->where('customer_payment',$value);
        });
    }
    public function payment_system($value){
        $this->model->where(function ($query) use ($value) {
            $query->where('payment_system',$value);
        });
    }
    public function price_total($value){
        $this->model->where(function ($query) use ($value) {
            $query->where('price_total',$value < 0 ? '<=' : '>=',$value);
        });
    }
    public function receipts_total($value){
        $this->model->where(function ($query) use ($value) {
            $query->where('receipts_total',$value < 0 ? '<=' : '>=',$value);
        });
    }
    public function exp_total($value){
        $this->model->where(function ($query) use ($value) {
            $query->where('exp_total',$value < 0 ? '<=' : '>=',$value);
        });
    }
    public function volumes_total($value){
        $this->model->where(function ($query) use ($value) {
            $query->where('volumes_total',$value < 0 ? '<=' : '>=',$value);
        });
    }
    public function payoff_total($value){
        $this->model->where(function ($query) use ($value) {
            $query->where('payoff_total',$value < 0 ? '<=' : '>=',$value);
        });
    }
    public function payed_off_total($value){
        $this->model->where(function ($query) use ($value) {
            $query->where('payed_off_total',$value < 0 ? '<=' : '>=',$value);
        });
    }
    public function to_payoff_total($value){
        $this->model->where(function ($query) use ($value) {
            $query->where('to_payoff_total',$value < 0 ? '<=' : '>=',$value);
        });
    }

    public function period($value){
        $period = explode(',',$value);

        $this->model->where(function ($query) use ($period) {
            $this->model->whereBetween('date_start',[Carbon::make($period[0]),Carbon::make($period[1])]);
        });
    }

    public function starts_after($value){$this->model->where('date', '<=', Carbon::parse($value));}
   /* public function phone($value){
        $this->phone = $value;
        $this->model->where(function ($query){
            $query->where('phone', 'like', '%'.$this->phone.statistic_initTable'%')->orWhere('phone_ext', 'like', '%'.$this->phone.'%')
                ->orWhereHas('act', function ($query){
                    $query->where('phone', 'like', '%'.$this->phone.'%')->orWhere('phone_ext', 'like', '%'.$this->phone.'%');
                });
        });
    }*/


    public function index(){
        $this->model = Lid::with($this->relations)->orderBy('id','desc');

        if(request()->get('filter')){
            foreach (request()->get('filter') as $k => $data){
                call_user_func_array([__CLASS__,$k],[$data]);
            }
        }
//        print_r($this->model->toSql());

        $data =  $this->model->paginate('50', ['*'], '', request()->get('page'));
        return $data;
    }
}
