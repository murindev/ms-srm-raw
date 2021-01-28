<?php


namespace App\Http\Vuex;


use App\Http\Controllers\Controller;
use App\Models\Crm\Lid;
use Carbon\Carbon;
use App\Events\Lid as Event;

class LidVuex extends Controller
{
    private $data;
    private $relations = ['finance','acts.volumes'];

    public function index()
    {
        $this->data = \request()->get('data') ? \request()->get('data') : '' ;
        return call_user_func_array([__CLASS__,\request()->get('func')],[$this->data]);
    }
// base
    public function contract(){
        $today = Carbon::today()->toObject();
        $dayLidCount = Lid::whereBetween('date_start', [Carbon::make($today->formatted), Carbon::make($today->formatted)
                ->add(1, 'day')])->get()->count() + 1;
        return mb_substr($today->year, 2) . '-' . $today->month . '-' . $dayLidCount . '-' . $today->day;
    }

    public function columns()
    {
        $results = \DB::select('
        select column_name, column_default from information_schema.columns
        where table_schema = ? and table_name = ? ', ['crm2', ' lids']
        );
        return collect($results)->pluck('column_default','column_name');
    }
    private function create(){
        return Lid::create($this->data);
    }
    private function read() {
        return Lid::where($this->data['field'], $this->data['value'])->get();
    }
    private function get(){
        return Lid::with($this->relations)->where('id', $this->data['id'])->first();
    }
    private function first(){
        return Lid::with($this->relations)->where('id', $this->data['id'])->first();
    }
    private function update(){
        return Lid::where('id',$this->data['id'])->update($this->data);
    }

    private function delete() {
        return Lid::where('id',$this->data['id'])->delete();
    }
    public function search(){
        $r = new Lid();
        $fields = $r->searchable;
        return $r::where(function ($query) use ($fields){
            foreach ($fields as $field){
                $query->orWhere($field, 'like','%'.$this->data.'%');
            }
        })->get();
    }

    public function searchField(){
        return Lid::where($this->data['field'],$this->data['value'])->get();
    }
    public function searchFieldLike(){
        return Lid::where($this->data['field'],'like','%'.$this->data['value'].'%')->get();
    }
// eof base

    public function getRow(){
        return $this->get();
    }

    public function fetchUpdateRow(){
        $this->update();
        return Lid::where('id',$this->data['id'])->first();
    }



    public function eCreateRow(){
        Event::broadcast(['action' => 'create', 'data' => $this->create()]);
    }
    public function eUpdateRow(){
        $this->update();
        Event::broadcast(['action' => 'update', 'data' => $this->get()]);
    }
    public function private_eUpdateRow(){
        $this->update();
        Event::broadcast(['action' => 'update_private', 'data' => $this->first()]);
    }
//    public function update(){
//        return Customer::where('id',$this->data['id'])->update($this->data);
//    }



}
