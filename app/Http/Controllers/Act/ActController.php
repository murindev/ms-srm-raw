<?php


namespace App\Http\Controllers\Act;


use App\Http\Controllers\Controller;
use App\Models\Crm\Act;
use App\Models\Crm\Implement;
use App\Models\Crm\Volume;
use Carbon\Carbon;

class ActController extends Controller
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
        isset($request['values']) ? $this->validateValues($request['values']) : $this->values = '';
        isset($request['field']) ? $this->field = $request['field'] : $this->field = '';

        return call_user_func_array([__CLASS__,$func],[$this->values]);
    }

    public function validateValues($value){
        $res = [];
        foreach ($value as $key => $val){
            switch ($key){
                case 'phone_ext':
                case 'phone':
                    $newVal = preg_replace("/[^,.0-9]/", '', $val);
                    break;
                case 'implement_act_file':
                case 'booking_act_file':
                $newVal = $val != '' ? $val:null;
                break;
                case 'destination':
                    $newVal = round($val,2) ;
                    break;
                default:
                    $newVal = $val;
            }

            $res[$key] = $newVal;
        }

        $this->values = $res;
    }


    public function getActs($request){
        return  Act::with('volumes','implements')->where('parent',$this->value)->get()->keyBy('id')->toArray();
    }

    public function create(){
        return Act::create($this->values)->id;
    }
    public function remove(){
        Volume::where('parent',$this->id)->delete();
        Implement::where('parent',$this->id)->delete();
        return Act::where('id',$this->id)->delete();
    }

    public function updateField(){
        $val = $this->value == '' ? null : $this->value;
        $update = Act::where('id',$this->id)->update([$this->field => $val]);
//        return $update ? $this->getActByID() : false;
        return $this->getActByID();
    }

    public function update(){
        unset($this->values['implements']);
        unset($this->values['volumes']);
        unset($this->values['geo']);
        unset($this->values['scheduled']);
        $update = Act::where('id',$this->id)->update($this->values);
        return $update ? $this->getActByID() : 0;
    }

    public function getActByID(){
        return Act::with('volumes','implements')->where('id',$this->id)->first();
    }
    public function copyMany(){
        $acts = Act::with('volumes')->whereIn('id',$this->value)->get();
        $cnt = $acts->count();
        $lid = 0;
        foreach ($acts as $act){
            $newAct = $act->replicate();
            $newAct->act_nr = $cnt+1;
            $newAct->save();
            $volumes = $act->getRelation('volumes');
            foreach ($volumes as $volume){
                $newVolume = $volume->replicate();
                $newVolume->parent = $newAct->id;
                $newVolume->save();
            }
            $cnt++;
            $lid = $act->parent;
        }

        return $lid;
    }

    public function map(){


    }

/*    public function ddd(){
        $volume = Implement::whereBetween('start_date', [Carbon::make($this->date), Carbon::make($this->date)->add(1, 'day')])
            ->where('master', $master)
            ->orderBy('start_date')
            ->get()->toArray();
    }*/


}
