<?php


namespace App\Http\Controllers\Expenses;


use App\Http\Controllers\Controller;
use App\Models\Expenses\Expenses;
use App\Models\Helper\Expense;

class ExpenseController extends Controller
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
        isset($request['values']) ? $this->values = $request['values'] : $this->values = '';
        isset($request['value']) ? $this->value = $request['value'] : $this->value = '';
        isset($request['field']) ? $this->field = $request['field'] : $this->field = '';

        return call_user_func_array([__CLASS__,$func],[$this->values]);
    }

    public function helperCreate(){
        return Expense::create($this->values)->id;
    }
    public function helperRead(){
        return Expense::all();
    }
    public function helperUpdate(){
        return Expense::where('id',$this->id)->update($this->values);
    }
    public function helperDelete(){
        return Expense::where('id',$this->id)->delete();
    }

    public function create(){
        return Expenses::create($this->values)->id;
    }
    public function read(){
        return Expenses::where('date',$this->value)->get();
    }
    public function update(){
        return Expenses::where('id',$this->id)->update($this->values);
    }
    public function delete(){
        return Expenses::where('id',$this->id)->delete();
    }

}
