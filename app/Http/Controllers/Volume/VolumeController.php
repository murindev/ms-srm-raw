<?php


namespace App\Http\Controllers\Volume;


use App\Http\Controllers\Controller;
use App\Models\Crm\Volume;

class VolumeController extends Controller
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
                case 'method_rrr':
                    $newVal = count($val) ? implode(',',$val) : null;
                    break;
                default:
                    $newVal = $val;
            }

            $res[$key] = $newVal;
        }

        $this->values = $res;
    }

    public function create(){
        return Volume::create($this->values)->id;
    }


    public function get(){
        return Volume::where('id',$this->id)->first();
    }

    public function update(){
        $update = Volume::where('id',$this->id)->update($this->values);
        return $update ? $this->get() : 0;
    }
    public function remove(){
        return Volume::where('id',$this->id)->delete();
    }

}
