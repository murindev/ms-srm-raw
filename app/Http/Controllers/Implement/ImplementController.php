<?php


namespace App\Http\Controllers\Implement;


use App\Http\Controllers\Controller;
use App\Models\Crm\Implement;
use Carbon\Carbon;

class ImplementController extends Controller
{

    public $id;
    public $values;
    public $field;
    private $value;
    private $date;
    private $dateStart;
    private $dateEnd;


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
        isset($request['start_date']) ? $this->dateStart = $request['start_date'] : $this->dateStart = '';
        isset($request['end_date']) ? $this->dateEnd = $request['end_date'] : $this->dateEnd = '';
        isset($request['date']) ? $this->date = $request['date'] : $this->date = '';

        return call_user_func_array([__CLASS__,$func],[$this->values]);
    }

    public function validateValues($value){
        unset($value['lat']);
        unset($value['lon']);
        unset($value['status']);

        $res = [];
        foreach ($value as $key => $val){
            switch ($key){
                case 'some_value':
                    $newVal = round($val,2) ;
                    break;
                default:
                    $newVal = $val;
            }
            $res[$key] = $newVal;
        }
        $this->values = $res;
    }

    public function create(){
        return Implement::create($this->values)->id;
    }
    public function read(){
        return Implement::where('id',$this->id)->first();
    }
    public function update(){

        $update = Implement::where('id',$this->id)->update($this->values);
        return $update ? $this->get() : 0;
    }
    public function delete(){
        return Implement::where('id',$this->id)->delete();
    }





    public function get(){
        return Implement::where('id',$this->id)->first();
    }





    public function byDate(){
        return Implement::whereBetween('start_date', [Carbon::make($this->date), Carbon::make($this->date)->add(1, 'day')])->orderBy('start_date')->get();
    }





}
