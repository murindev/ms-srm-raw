<?php


namespace App\Http\Vuex;


use App\Http\Controllers\Controller;
use App\Models\Crm\Volume;
use App\Events\Volume as Event;

class VolumeVuex extends Controller
{
    private $data;

    public function index()
    {
        $this->data = \request()->get('data') ? \request()->get('data') : '' ;
        return call_user_func_array([__CLASS__,\request()->get('func')],[$this->data]);
    }
// base

    public function columns()
    {
        $results = \DB::select(' 
        select column_name, column_default from information_schema.columns 
        where table_schema = ? and table_name = ? ', ['crm2', ' implements']
        );
        return collect($results)->pluck('column_default','column_name');
    }
    private function create(){
        return Volume::create($this->data);
    }
    private function read() {
        return Volume::where($this->data['field'], $this->data['value'])->get();
    }
    private function get(){
        return Volume::where('id', $this->data['id'])->first();
    }
    private function update(){
        return Volume::where('id',$this->data['id'])->update($this->data);
    }

    private function delete() {
        return Volume::where('id',$this->data['id'])->delete();
    }
    public function search(){
        $r = new Volume();
        $fields = $r->searchable;
        return $r::where(function ($query) use ($fields){
            foreach ($fields as $field){
                $query->orWhere($field, 'like','%'.$this->data.'%');
            }
        })->get();
    }

    public function searchField(){
        return Volume::where($this->data['field'],$this->data['value'])->get();
    }
// eof base

    public function eCreateRow(){
        Event::broadcast(['action' => 'create', 'data' => $this->create()]);
    }
    public function eUpdateRow(){
        $this->update();
        Event::broadcast(['action' => 'update', 'data' => $this->get()]);
    }
    public function eDeleteRow(){
        $deletedRow = $this->get();
        if($this->delete()){
            Event::broadcast(['action' => 'delete', 'data' =>  $deletedRow]);
        } else {
            Event::broadcast(['action' => 'delete', 'data' =>  false]);
        }

    }

//    public function update(){
//        return Customer::where('id',$this->data['id'])->update($this->data);
//    }



}
