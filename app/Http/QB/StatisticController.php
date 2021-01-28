<?php


namespace App\Http\QB;


use App\Http\Controllers\Controller;
use App\Models\Crm\LidStatistic;
use Carbon\Carbon;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class StatisticController extends Controller
{
    private $relations = ['lid','customer','act.implements','volumes','finance'];
    public $model;
    public $phone;
    public $customer_status;
    public $name;
    public $customer_address;
    public $organization;
    public $email;
    public $property_type;
    public $pest;
    public $cash;
    public $cash_tag;
    public $cashless;
    public $cashless_tag;
    public $sum_total;
    public $card_payment;
    public $overpay;
    public $contractor_fee;
    public $contract;
    public $implement_date;
    public $period;

    public function index2(){

        $tb = QueryBuilder::for(LidStatistic::class)
//            ->join('acts', 'acts.statistic_id', 'lid_statistics.id')
//            ->allowedIncludes($this->relations)
            ->allowedFilters([
                // exacts
                AllowedFilter::exact('date'),
                AllowedFilter::exact('site'),
                AllowedFilter::exact('manager'),
                AllowedFilter::exact('call_status'),
                AllowedFilter::exact('app_status'),
//                AllowedFilter::partial('act.phone'),
                // scopes
                AllowedFilter::scope('starts_after'),
//                AllowedFilter::scope('phones_raw'),
                //like
                'cause',
                'phone',
            ])
            ->allowedSorts('date')
            ->with($this->relations)
//            ->orderBy('date','desc')
//            ->paginate('12', ['*'], '', request()->get('page') ? $page = request()->get('page') : $page = 1);
            ->paginate('50', ['*'], '', request()->get('page'));

        return $tb;

    }



    public function row($value){

        $this->model->where('id', '=', $value);
    }
    public function starts_after($value){$this->model->where('date', '<=', Carbon::parse($value));}
    // Сайт
    public function site($value){$this->model->where('site', $value);}
    // Менеджер
    public function manager($value){$this->model->where('manager', $value);}
    // call_status
    public function call_status($value){$this->model->where('call_status', $value);}
    // app_status
    public function app_status($value){$this->model->where('app_status', $value);}
    // cause
    public function cause($value){$this->model->where('cause', 'like', '%'.$value.'%');}
    // Телефон
    public function phone($value){
        $this->phone = $value;
        $this->model->where(function ($query){
            $query->where('phone', 'like', '%'.$this->phone.'%')->orWhere('phone_ext', 'like', '%'.$this->phone.'%')
                ->orWhereHas('act', function ($query){
                    $query->where('phone', 'like', '%'.$this->phone.'%')->orWhere('phone_ext', 'like', '%'.$this->phone.'%');
                });
        });
    }
    //customer_status
    public function customer_status($value)
    {
        $this->customer_status = $value;
        if($value == 3){
            $this->model->where(function ($query) {
                $query->where('customer_id', null);
            });
        } else {
            $this->model->where(function ($query) {
//                $query->where('customer_status', $this->customer_status)
                   $query->orWhereHas('customer', function ($query) {
                        $query->where('status', $this->customer_status);
                    });
            });
        }

    }
    // name
    public function name($value){
        $this->name = $value;
        $this->model->where(function ($query){
            $query->where('name', 'like', '%'.$this->name.'%')
                ->orWhereHas('act', function ($query){
                    $query->where('contact_name', 'like', '%'.$this->name.'%');
                });
        });


    }
    // address
    public function address($value){
        $this->customer_address = $value;
        $this->model->where(function ($query){
            $query->where('address', 'like', '%'.$this->customer_address.'%')
                ->orWhereHas('act', function ($query){
                $query->where('address', 'like', '%'.$this->customer_address.'%');
            });
        });
    }
    // organization
    public function organization($value){
        $this->organization = $value;
        $this->model->where(function ($query){
            $query->where('organization', 'like', '%'.$this->organization.'%')
                ->orWhereHas('customer', function ($query){
                    $query->where('organization_long', 'like', '%'.$this->organization.'%')->orhere('organization', 'like', '%'.$this->organization.'%');
                });
        });
    }
    // email
    public function email($value){
        $this->email = $value;
        $this->model->whereHas('customer', function ($query){
            $query->where('email', 'like', '%'.$this->email.'%')->orWhere('emails', 'like', '%'.$this->email.'%');
        });
    }
    // property_type
    public function property_type($value){
        $this->property_type = $value;
        $this->model->where(function ($query){
            $query->whereHas('act', function ($query){
                $query->where('property_type', '=', $this->property_type);
            })->orWhere('property_type', '=', $this->property_type);
        });
    }
    // pest
    public function pest($value){
        $this->pest = $value;
        $this->model->where(function ($query){
            $query->whereHas('volume', function ($query){
                $query->where('pest', '=', $this->pest);
            });
        });
    }
    // cash
    public function cash($value){
        $this->cash = $value;
        $this->model->where(function ($query){
            $query->whereHas('volumes', function ($query){
                $query->where('price_fact', '>=', $this->cash);
            });
        });
    }
    // cashless
    public function cashless($value){
        $this->cash = $value;
        $this->model->where(function ($query){
            $query->whereHas('volumes', function ($query){
                $query->where('price_standard', '>=', $this->cash);
            });
        });
    }

    //card_payment
    public function card_payment($value){
        if($value){
            $this->model->where(function ($query){
                $query->whereHas('act', function ($query){
                    $query->where('card_payment', '=', 1);
                });
            });
        }
    }

    //overpay
    public function overpay($value){
        $this->model->where(function ($query) use ($value){
            $query->whereHas('act', function ($query) use ($value){
                $query->where('overpay', '>=', $value);
            });
        });
    }
    //contractor_fee
    public function contractor_fee($value){
        $this->model->where(function ($query) use ($value){
            $query->whereHas('act', function ($query) use ($value){
                $query->where('contractor_fee', '>=', $value);
            });
        });
    }

    //period
    public function period($value){
        $this->period = explode(',',$value);

        $this->model->where(function ($query){
        $this->model->whereBetween('date',[Carbon::make($this->period[0]),Carbon::make($this->period[1])]);
        });
    }
    //contract
    public function contract($value){
        $this->model->where(function ($query) use ($value){
            $query->whereHas('lid', function ($query) use ($value){
                $query->where('contract', 'like', '%'.$value.'%');
            });
        });
    }
    public function implement_date($value){
        $this->model->where(function ($query) use ($value){
            $query->whereHas('act.implement', function ($query) use ($value){
                $query->where('start_date', 'like', '%'.$value.'%');
            });
        });
    }







//->where('statistic_id', '=', $this->pest)
    public function index(){
        $this->model = LidStatistic::with($this->relations)->orderBy('id','desc');

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
