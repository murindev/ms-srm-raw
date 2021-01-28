<?php


namespace App\Http\Controllers\Statistics;


use App\Http\Controllers\Controller;
use App\Models\Crm\Act;
use App\Models\Crm\Implement;
use App\Models\Crm\Lid;
use App\Models\Crm\LidStatistic;
use App\Models\Crm\Volume;
use App\Models\Finance\Finance;
use function Sodium\add;

class StatisticFinance extends Controller
{


    private $period;

    public $byFinActivity;
    public $byFinTotal;
    public $byFinImplement;


    public function __construct()
    {
        $this->byFinActivity = collect();
        $this->byFinTotal = collect();
        $this->byFinImplement = collect();
        $this->period = request()->get('period') ? [request()->get('period')['start'], request()->get('period')['end']] : null;
    }


    public function index()
    {
        $this->byFinActivity();


        $this->byFinTotal();
        $this->byFinManagers();

        $this->totalImplemented();
        return json_encode($this);
    }

    public function test()
    {
        $this->byFinActivity();
        dump($this);
    }


    public function byFinActivity()
    {

        $stats = LidStatistic::where('call_status', 7)->where('lid_id', '!=', null);
        $this->period && $stats->whereBetween('date', $this->period);

        $lidIds = $stats->selectRaw('distinct(lid_id)')->pluck('lid_id');

        // filter only new lids by this period
        $lids = Lid::whereIn('id', $lidIds);
        $this->period && $lids->whereBetween('date_start', $this->period);

        $acts = Act::whereIn('parent', $lids->selectRaw('distinct(id)'));

        $newTotal = [];
        $total = $acts->get()->groupBy('creator');
        foreach ($total as $k => $managerActs){
            $nk = $k ? $k : 'null';
            $newTotal[$nk] = Volume::whereIn('parent', $managerActs->pluck('id'))
                ->selectRaw('sum(price_fact) as cash')
                ->selectRaw('sum(price_standard) as cashless')
                ->first();
        }

        $newImplemented = [];
        $implemented = $acts->where('finished', 1)->get()->groupBy('creator');
        foreach ($implemented as $k => $managerActs){
            $nk = $k ? $k : 'null';
            $newImplemented[$nk] = Volume::whereIn('parent', $managerActs->pluck('id'))
                ->selectRaw('sum(price_fact) as cash')
                ->selectRaw('sum(price_standard) as cashless')
                ->first();
        }

//        $newImplemented = $acts->where('finished', 1)->get()->groupBy('creator')->map(function ($managerActs, $k) {
//            return Volume::whereIn('parent', $managerActs->pluck('id'))
//                ->selectRaw('sum(price_fact) as cash')
//                ->selectRaw('sum(price_standard) as cashless')
//                ->first();
//        });

        // Создано актов (за период ) (количество.сумма)
        $this->byFinActivity->put('newTotal', $newTotal);

        // Выполнено актов (за период ) (количество.сумма)
        $this->byFinActivity->put('newImplemented', $newImplemented);

        // Оплачено  актов (за период ) (количество.сумма) 'newPayed' => [],

    }


    public function byFinTotal()
    {
        $finTotalDebitCash = Finance::where('type', 1)->where('form', 1);
        $this->period && $finTotalDebitCash->whereBetween('created_at', $this->period);

        $finTotalDebitCashless = Finance::where('type', 1)->where('form', 2);
        $this->period && $finTotalDebitCashless->whereBetween('created_at', $this->period);

        $finTotalCreditCashless = Finance::where('type', 2)->where('form', 2);
        $this->period && $finTotalCreditCashless->whereBetween('created_at', $this->period);

        $finTotalCreditCash = Finance::where('type', 2)->where('form', 1);
        $this->period && $finTotalCreditCash->whereBetween('created_at', $this->period);

        $financesTotal = [
            'debitCash' => $finTotalDebitCash->sum('amount'),
            'debitCashless' => $finTotalDebitCashless->sum('amount'),
            'creditCashless' => $finTotalCreditCashless->sum('amount'),
            'creditCash' => $finTotalCreditCash->sum('amount'),
        ];

        $this->byFinTotal->put('byFinTotal', $financesTotal);


        /*
                // В ЦЕЛОМ находится в производстве
                $this->byFinTotal = [
                    // Назначено к выполнению  (количество.сумма)
                    'total' => [],
                    // Оплачено  (количество.сумма)
                    'totalPaid' => [],
                    // Выполнено (количество.сумма)
                    'totalImplemented' => [],
                    // Осталось выполнить актов (за период ) (количество.сумма)
                    'totalToImplement' => [],
                    // Осталось выполнить ВСЕ актов (за период ) (количество.сумма)
                    'totalShouldImplement' => [],
                ];*/
    }

    public function byFinManagers()
    {
        $finByManagers = Finance::where('act_id', '!=', null)->where('act_id', '!=', '');
        $this->period && $finByManagers->whereBetween('created_at', $this->period);

        $financesManagers = $finByManagers->get()->map(function ($f) {

            foreach ($f->act_id as $actId) {

                $act = Act::with(['volume'])->whereId($actId)->first();

                if ($actId && $act && $act->creator != '') {
                    $temp = collect();
                    $price_cash = $act && $act->volume ? $act->volume->sum('price_fact') : 0;
                    $price_cashless = $act && $act->volume ? $act->volume->sum('price_standard') : 0;

                    $temp->put('form', $f->form);
                    $temp->put('type', $f->type);
                    $temp->put('amount', $f->amount);
                    $temp->put('act', $actId);
                    $temp->put('creator', $act->creator);
                    $temp->put('contractor_fee_cash', $act ? $act->contractor_fee_cash : 0);
                    $temp->put('contractor_fee_cashless', $act ? $act->contractor_fee_cashless : 0);
                    $temp->put('pay_back_cash', $act ? $act->pay_back_cash : 0);
                    $temp->put('pay_back_cashless', $act ? $act->pay_back_cashless : 0);
                    $temp->put('overpay', $act ? $act->overpay : 0);
                    $temp->put('overpay_rest', $act ? $act->overpay_rest : 0);
                    $temp->put('price_cash', $price_cash);
                    $temp->put('price_cashless', $price_cashless);

                    return $temp;

                }


            }
        })->groupBy('creator');

        $finByManagersArr = [];
        foreach ($financesManagers as $k => $fbm) {
            $finByManagersArr[$k] = [
                'debitCash' => $fbm->where('type', 1)->where('form', 1)->sum('price_cash'),
                'debitCashless' => $fbm->where('type', 1)->where('form', 2)->sum('price_cashless'),

                'creditCash' => $fbm->sum('contractor_fee_cash'),
                'creditCashless' => $fbm->sum('contractor_fee_cashless'),
            ];
        }

        $this->byFinTotal->put('byFinManagers', $finByManagersArr);
    }





    public function totalImplemented(){
        $implemented = Implement::where('parent','!=', null);
        $this->period && $implemented->whereBetween('end_date',$this->period);
        $implementedActs = $implemented->get()->pluck('parent')->toArray();
        $implementedActs = array_unique($implementedActs);


        $actsFinished = Act::with(['volume'])->whereIn('id',$implementedActs)->get()->map(function ($q){
            $arr = collect();
            $price_cash = $q->volume->sum('price_fact');
            $price_cashless = $q->volume->sum('price_standard');


            $arr->put('creator',$q->creator);
            $arr->put('cash',$price_cash);
            $arr->put('cashless',$price_cashless);
            if($q->finished == 1){
                $arr->put('finishedCash',$price_cash);
                $arr->put('finishedCashless',$price_cashless);
            } else{
                $arr->put('finishedCash',0);
                $arr->put('finishedCashless',0);
            }
            return $arr;
        });

        $total = $actsFinished;
        $byManagers = $actsFinished->groupBy('creator');

        $totalFinished = [];
        foreach ($byManagers as $k => $finByActs){
            $totalFinished[$k] = $finByActs->pipe(function ($collection){
                return collect([
                    'cash' => $collection->sum('cash'),
                    'cashless' => $collection->sum('cashless'),
                    'finishedCash' => $collection->sum('finishedCash'),
                    'finishedCashless' => $collection->sum('finishedCashless')
                ]);
            });
        }
        $this->byFinImplement->put('byImplementManagers', $totalFinished  );





        $this->byFinImplement->put('byImplementTotal', $total->pipe(function ($collection){
            return collect([
                'cash' => $collection->sum('cash'),
                'cashless' => $collection->sum('cashless'),
                'finishedCash' => $collection->sum('finishedCash'),
                'finishedCashless' => $collection->sum('finishedCashless')
            ]);
        })  );


    }


}
