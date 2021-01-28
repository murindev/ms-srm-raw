<?php


namespace App\Http\Controllers\Crew;


use App\Http\Controllers\Controller;
use App\Models\Crew\CrewScheduling;
use App\Models\Crm\Act;
use App\Models\Crm\Implement;
use App\Models\Crm\Lid;
use App\Models\Expenses\Expenses;
use App\Models\Helper\Expense;
use App\Models\Rules\Rule;
use Carbon\Carbon;

class CrewSchedulingController extends Controller
{
    public $id;
    public $values;
    public $field;
    private $value;

    /**
     * @return mixed
     */
    public function async(){
        $request = request()->all();
        $func = $request['func'];
        isset($request['id']) ? $this->id = $request['id'] : $this->id = '';
        isset($request['value']) ? $this->value = $request['value'] : $this->value = '';
        isset($request['values']) ? $this->values = $request['values'] : $this->values = '';
        isset($request['field']) ? $this->field = $request['field'] : $this->field = '';

        return call_user_func_array([__CLASS__,$func],[$this->values]);
    }

    public function fetch(){

        $imp = Implement::whereBetween('start_date',[$this->values['start'],Carbon::make($this->values['end'])->add(1,'day')])->get();


        $expFixed = Expenses::whereBetween('date',[substr($this->values['start'],0,7),substr(Carbon::make($this->values['end'])->add(1,'day'),0,7)])->where('type','fixed')->get();

        $expDaily = Expenses::whereBetween('date',[$this->values['start'],Carbon::make($this->values['end'])])->where('type','daily')->get();
//        $expCollection = $exp->coll



//        $collection = new \Illuminate\Support\Collection();
        $byID = $imp->groupBy('id')->toArray();
        $byMaster = $imp->groupBy('master')->toArray();
        $byDate = $imp->groupBy('date')->toArray();
        $byActs = $imp->groupBy('parent')->toArray();
        $byLid = $imp->groupBy('lid_id')->toArray();
        $arImplementsID = array_keys($byID);
        $arActsID = array_keys($byActs);
        $arLidsID = array_keys($byLid);
        $acts = Act::with('volumes')->whereIn('id',$arActsID)->get()->keyBy('id')->toArray();
        $lids = Lid::whereIn('id',$arLidsID)->get()->keyBy('id')->toArray();
        $crewScheduling = CrewScheduling::whereBetween('date',[$this->values['start'],Carbon::make($this->values['end'])->add(1,'day')])->get();
        $rules = Rule::where('subject','implement')->whereIn('subject_id',$arImplementsID)->get();
        $crewFormatted = [];
        $byMasterAkt = [];
        $byActCount = [];
        $byActMaster = [];
        $impByDateMaster = [];
        $actCost = [];
        $impLength = [];
        $impRules = [];


        foreach ($byDate as $dateKey => $implements) {
            foreach ($implements as  $implement) {
                $impByDateMaster[$dateKey][$implement['master']][] = $implement; //
            }
        }


        foreach ($imp->keyBy('id') as $impKey => $i ) {
            $impLength[$impKey] = (Carbon::parse($i['end_date'])->diffInMinutes($i['start_date'])) / 60 ;
        }

        foreach ($acts as $actId => $act) {
            $sun = 0;
            foreach ($act['volumes'] as $vol) {
                $cost = $vol['price_fact'] ? $vol['price_fact'] : 0;
                $sun = $sun + $cost;
            }
            $actCost[$actId] = $sun;
        }

        foreach ($byActs as $actKey => $actToCnt){
            $byActCount[$actKey] = count($actToCnt);
        }
        foreach ($byActs as $actKey => $imps){
            foreach ($imps as $i) {
                $byActMaster[$actKey][$i['master']] = $i;
            }
        }
        foreach ($byMaster as $masterKey => $arr){
            foreach ($arr as $v){
                $byMasterAkt[$masterKey][$v['date']][] = $v['parent'];
            }
        }

        foreach ($crewScheduling->groupBy('user_id') as $userKey => $byUser){
            foreach ($byUser as $userData){
                $crewFormatted[$userKey][$userData['date']] = $userData;
            }

        }
        foreach ($rules->groupBy('implement_id') as $idKey => $rules){
            foreach ($rules as $rule) {
                $impRules[$idKey][$rule['rule_id']] = $rule;
            }
        }
        $eFixed = [];
        $eDaily = [];

        foreach ($expFixed as $f){
            $eFixed[$f['date']][] = $f;
        }
        foreach ($expDaily as $d){
            $eDaily[$d['date']][] = $d;
        }

        return [
            'crewScheduling' => $crewFormatted,
            'implements' => $imp->keyBy('id'),
            'byDate' => $byDate,
            'byMasterAkt' => $byMasterAkt,
            'byActMaster' => $byActMaster,
            'actImplementsCount' => $byActCount,
            'acts' => $acts,
            'lids' => $lids,
            'actCost' => $actCost,
            'impLength' => $impLength,
            'impRules' => $impRules,
            'impByDateMaster' => $impByDateMaster,
            'expFixed' => $eFixed,
            'expDaily' => $eDaily,
            'expHelpers' => Expense::all()->keyBy('id'),
        ];

    }

    public function set(){
        $row = CrewScheduling::find('user_id',$this->values['user_id'])->where('date',$this->values['date']);
//        if($row){}
        return $row ? $row->id : null;

    }

    public function create(){
        $create = CrewScheduling::create($this->values);
        return CrewScheduling::find($create->id);
    }

    public function update(){
        CrewScheduling::where('id',$this->id)->update($this->values);
        return CrewScheduling::find($this->id);
    }

    /*
     * Зп по работнику
     *  - день
     *  -период
     * Зп по всем рпботникам за период
     */


}
