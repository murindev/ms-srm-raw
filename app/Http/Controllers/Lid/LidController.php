<?php


namespace App\Http\Controllers\Lid;


use App\Http\Controllers\Controller;
use App\Models\Crm\Act;
use App\Models\Crm\Customer;
use App\Models\Crm\Implement;
use App\Models\Crm\Lid;
use App\Models\Crm\Volume;
use App\Models\Settings;

class LidController extends Controller
{
    public $id;
    public $values;
    public $manager;

    public function async(){
        $request = request()->all();
        $func = $request['func'];
        isset($request['id']) ? $this->id = $request['id'] : $this->id = '';
        isset($request['manager']) ? $this->manager = $request['manager'] : $this->manager = '';
        isset($request['values']) ? $this->values = $request['values'] : $this->values = '';
        return call_user_func_array([__CLASS__,$func],[$this->values]);
    }

    public function get(){
       return Lid::with('payments')->where('id',$this->id)->first();
    }
    public function update(){
        unset($this->values['payments']);
        return Lid::whereId($this->id)->update($this->values);
    }
    public function proofUpdate($request){
        unset($request['values']['id']);
        unset($this->values['payments']);
        return $this->update($request);
    }

    public function clone()
    {
        $lid = Lid::with('act.volumes')->find($this->id)->replicate();
        $lid->save();
        $acts = $lid->getRelation('act');
        foreach ($acts as $act) if ($act->subact === null) {
            $newAct = $act->replicate();
            $newAct->parent = $lid->id;
            $newAct->save();
            $volumes = $act->getRelation('volumes');
            foreach ($volumes as $volume) {
                $newVolume = $volume->replicate();
                $newVolume->parent = $newAct->id;
                $newVolume->lid_id = $lid->id;
                $newVolume->save();
            }
        }
        return $lid->id;
    }


    public function delete(){
        Volume::where('lid_id',$this->id)->delete();
        Implement::where('lid_id',$this->id)->delete();
        Act::where('parent',$this->id)->delete();
        return Lid::where('id',$this->id)->delete();
    }

    public function createSettings(){
        Settings::create([
            'type' => 'lid',
            'manager' => $this->manager,
//            'settings' => ''
        ]);
    }
    public function readSettings(){
        $res = Settings::where('manager',$this->manager)->where('type','lid')->first();
        if(!$res){
            $this->createSettings();
        }
        return $res;
    }


    public function updateSettings(){
        Settings::where('manager',$this->manager)->where('type','lid')->update(['settings' => json_encode($this->values)]);
        return $this->readSettings();
    }


    public function listSettingsCreate(){

    }
    public function listSettingsRead(){}

    public function listSettingsUpdate(){ }
    public function listSettingsDelete(){ }

    public function getList(){
        return Lid::orderBy('id','DESC')->get();
    }


    public function readCustomers(){
        $customer = Customer::all();
        return [
            'arCustomer' => $customer,
            'obCustomer' => $customer->keyBy('id'),

        ];
    }


}
