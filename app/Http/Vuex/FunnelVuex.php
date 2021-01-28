<?php


namespace App\Http\Vuex;


use App\Events\Finance;
use App\Http\Controllers\Controller;
use App\Models\Crm\Act;
use App\Models\Crm\Implement;
use App\Models\Crm\Lid;
use App\Models\Crm\LidStatistic;
use App\Models\Crm\Volume;
use Carbon\Carbon;


class FunnelVuex extends Controller
{
    private $data;

    public function index()
    {
        $this->data = \request()->get('data') ? \request()->get('data') : '' ;
        return call_user_func_array([__CLASS__,\request()->get('func')],[$this->data]);
    }
// base

    public function readStatistic(){
        $stat = LidStatistic::whereBetween('date',[Carbon::make($this->data['start']),Carbon::make($this->data['end'])]);
        if($this->data['company'] == 'manager'){
            $stat->where('manager',$this->data['manager']);
        }
        return $stat->get();
    }

    public function readFinance(){
        $fin = Finance::whereBetween('updated_at',[Carbon::make($this->data['start']),Carbon::make($this->data['end'])]);
        if($this->data['company'] == 'manager'){
            $fin->where('manager',$this->data['manager']);
        }
        return $fin->get();
    }

    public function getActs(){
        return Act::all();
    }
    public function getActsById(){
        return Act::with(['volume'])->get()->keyBy('id')->toArray();
    }
    public function getFinances(){
        return \App\Models\Finance\Finance::all();
    }
    public function getImplements(){
        return Implement::all();
    }
    public function getVolumes(){
        return Volume::all();
    }
    public function getStatistics(){
        return LidStatistic::with(['customer'])->get();
    }

    public function getLidWithActs(){
        return Lid::with('client','acts.volume','acts.implementFirst')->get();
    }


    public function getDataCollection(){
        $wholeActs = Act::with(['volumesMobile','implement'])->get();
        $wholeFinances = \App\Models\Finance\Finance::all();

        return [
            'acts' => $wholeActs,
            'finance' => $wholeFinances
        ];
    }


}
