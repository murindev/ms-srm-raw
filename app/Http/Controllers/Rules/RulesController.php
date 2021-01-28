<?php


namespace App\Http\Controllers\Rules;


use App\Http\Controllers\Controller;
use App\Models\Helper\Rules;
use \App\Models\Rules\Rule;
use App\Models\MongoLog\Log;

class RulesController extends Controller
{
    public $id;
    public $values;
    public $field;
    private $value;
    private $roles;

    /**
     * @return mixed
     */
    public function async(){
        $this->roles = Rules::all()->sortBy('sort')->groupBy('role');
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

    public function rolesSet($role){
        return $this->roles[$role];
    }

    public function create(){
        $id = Rule::create($this->values)->id;
        return Rule::where('id',$id)->first();
    }
    public function update(){
        $update = Rule::where('id',$this->id)->update($this->values);
        if($update) {
            return Rule::where('id',$this->id)->first();
        } else {
            return 0;
        }
    }
    public function delete(){
        $delete = Rule::where('id',$this->id)->delete();
    }




}
