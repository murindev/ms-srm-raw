<?php


namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Crm\Customer;
use http\Env\Request;
use Illuminate\Support\Facades\Schema;

class CustomerController extends Controller
{
    public $id;
    public $values;
    public $field;
    private $value;
    public function async(){
        $request = request()->all();
        $func = $request['func'];
        isset($request['id']) ? $this->id = $request['id'] : $this->id = '';
        isset($request['value']) ? $this->value = $request['value'] : $this->value = '';
        isset($request['values']) ? $this->validateValues($request['values']) : $this->values = '';
        isset($request['field']) ? $this->field = $request['field'] : $this->field = '';

        return call_user_func_array([__CLASS__,$func],[$this->values]);
    }

    public function getColumns(){
        return Schema::getColumnListing('customers');
    }
    public function emptyTable(){
        $arr = [];
        foreach ($this->getColumns() as $column){
            $arr[$column] = '';
        }
        return $arr;
    }

    public function validateValues($value){
        $res = [];
        foreach ($value as $key => $val){
            switch ($key){
                case 'phone_ext':
                case 'phone':
                    $newVal = preg_replace("/[^,.0-9]/", '', $val);
                    break;
                default:
                    $newVal = $val;
            }
            $res[$key] = $newVal;
        }

       $this->values = $res;
    }


    public function getByID(){
        return Customer::whereId($this->id)->first();
    }
    public function create()
    {
        return Customer::create($this->values)->id;
    }
    public function newCustomer()
    {
        return Customer::create($this->values);
    }

    public function getCreated(){
        $id = $this->create();
        return Customer::whereId($id)->first();
    }


    public function search()
    {
        return Customer::where($this->field, 'like', '%' . $this->value . '%')->get();
    }
    public function searchByField()
    {
        return Customer::where($this->field, $this->value)->get();
    }

    public function update(){
        return Customer::where('id',$this->id)->update($this->values);
    }
    public function updateField(){
        return Customer::where('id',$this->id)->update([$this->field => $this->value]);
    }

    public function check()
    {
        return Customer::where($this->field, $this->values)->get()->count();
    }

    public function save()
    {
    }

    public function saveField()
    {
    }

// v1.0
    public function q(){
        $r = new Customer();
        $fields = $r->searchable;
        return $r::where(function ($query) use ($fields){
            foreach ($fields as $field){
                $query->orWhere($field, 'like','%'.\request()->get('q').'%');
            }
        })->get();

    }

}
