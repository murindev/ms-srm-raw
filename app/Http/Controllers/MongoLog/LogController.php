<?php


namespace App\Http\Controllers\MongoLog;


use App\Http\Controllers\Controller;
use App\Models\Helper\Rules;
use App\Models\MongoLog\Log;
use App\Models\Rules\Rule;

class LogController extends Controller
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
                case 'some_field':
                case 'some_field_2':
                    $newVal = preg_replace("/[^,.0-9]/", '', $val);
                    break;
                default:
                    $newVal = $val;
            }

            $res[$key] = $newVal;
        }

        $this->values = $res;
    }

    // log ['id','model','state','user','field','value','storage','lid_id','act_id','implement_id','volume_id']

    public function create(){
        return Log::create($this->values)->_id;
    }
    public function update(){
        return Log::where('id',$this->id)->update($this->values);
    }

}
