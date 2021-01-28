<?php


namespace App\Http\Vuex;


use App\Http\Controllers\Controller;
use App\Models\Crm\Customer;
use App\Events\Customer as Event;
use App\Models\Crm\Lid;
use DemeterChain\C;

class CustomerVuex extends Controller
{
    private $data;
    private $logic;

    public function index(){
        $this->data = \request()->get('data') ? \request()->get('data') : '' ;
        $this->logic = \request()->get('logic') ? \request()->get('logic') : '' ;
        return call_user_func_array([__CLASS__,\request()->get('func')],[$this->data]);
    }
    // base
    public function columns()
    {
        $results = \DB::select('
        select column_name, column_default from information_schema.columns
        where table_schema = ? and table_name = ? ', ['crm2', 'customers']
        );
        return collect($results)->pluck('column_default','column_name');
    }
    public function search(){
        $r = new Customer();
        $fields = $r->searchable;
        return $r::where(function ($query) use ($fields){
            foreach ($fields as $field){
                $query->orWhere($field, 'like','%'.$this->data.'%');
            }
        })->get();
    }
    public function searchRow(){
        return Customer::where($this->data['field'],$this->data['value'])->first();
    }
    public function searchField(){
        return Customer::where($this->data['field'],$this->data['value'])->get();
    }

    private function create(){
        return Customer::create($this->data);
    }
    private function read() {
        return Customer::where($this->data['field'], $this->data['value'])->get();
    }
    private function get(){
        return Customer::where('id', $this->data['id'])->first();
    }
    private function update(){
        return Customer::where('id',$this->data['id'])->update($this->data);
    }
    private function delete() {
        return Customer::where('id',$this->data['id'])->delete();
    }

    private function fetchHasManyLids(){
        return Lid::where($this->data['field'],$this->data['value'])->get();
    }

    // eof base






    public function test() {
        Event::broadcast([
            'action' => 'customer test',
            'data' => 'from broadcast'
        ]);
        return 'i test from controller';
    }

    public function  eUpdate() {
        $this->update();
        Event::broadcast([
            'action' => 'update',
            'logic' => $this->logic,
            'data' => $this->get()
        ]);
    }
    public function eCreate() {
        Event::broadcast([
            'action' => 'create',
            'logic' => $this->logic,
            'data' => $this->create()
        ]);
    }

}
