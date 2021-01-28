<?php


namespace App\Http\Vuex;


use App\Http\Controllers\Controller;
use App\Events\Finance as Event;
use App\Models\Finance\Finance;

class FinanceVuex extends Controller
{
    private $data;

    public function index()
    {
        $this->data = \request()->get('data') ? \request()->get('data') : '';
        return call_user_func_array([__CLASS__, \request()->get('func')], [$this->data]);
    }

// base

    public function columns()
    {
        $results = \DB::select('
        select column_name, column_default from information_schema.columns
        where table_schema = ? and table_name = ? ', ['crm2', ' finances']
        );
        return collect($results)->pluck('column_default', 'column_name');
    }

    private function create()
    {
        return Finance::create($this->data);
//        if(array_key_exists('contract',$this->data) && $this->data['amount'] > 0){
//            return Finance::create($this->data);
//        } else {
//            throw new \Exception('"contract" is needed by default and amount should be > 0');
//        }
    }

    private function read()
    {
        return Finance::where($this->data['field'], $this->data['value'])->orderBy('created_at','desc')->get();
    }
    private function get()
    {
        return Finance::where('lid_id', $this->data['lid_id'])->orderBy('created_at','desc')->get();
    }


    private function first()
    {
        return Finance::where('id', $this->data['id'])->first();
    }

    private function update()
    {
        $this->data['act_id'] =  implode(',', $this->data['act_id']);
        return Finance::where('id', $this->data['id'])->update($this->data);
    }

    private function delete()
    {
        return Finance::where('id', $this->data['id'])->delete();
    }

    private function deactivate()
    {
        return Finance::where('id', $this->data['id'])->update(['active' => null]);
    }
    private function activate()
    {
        return Finance::where('id', $this->data['id'])->update(['active' => 1]);
    }

    public function search()
    {
        $r = new Finance();
        $fields = $r->searchable;
        return $r::where(function ($query) use ($fields) {
            foreach ($fields as $field) {
                $query->orWhere($field, 'like', '%' . $this->data . '%');
            }
        })->get();
    }

    public function searchField()
    {
        return Finance::where($this->data['field'], $this->data['value'])->get();
    }

// eof base

    public function createRow(){
        $this->create();
        return $this->get();
    }

    public function readTable(){
        return $this->read();
    }

    public function readUpdateRow(){
        if($this->data['amount'] == 0){
            $this->delete();
        } else {
            $this->update();
        }
        return $this->get();
    }





/*


    public function fetchUpdateRow()
    {
        $this->update();
        return Finance::where('id', $this->data['id'])->first();

    }

    public function fetchSearchRow()
    {
        return Finance::where($this->data['field'], $this->data['value'])->first();
    }

    public function eCreateRow()
    {
        Event::broadcast(['action' => 'finance', 'data' => $this->create()]);
    }


    public function eUpdateRow()
    {
        $this->update();
        Event::broadcast(['action' => 'finance', 'data' => $this->get()]);
    }
//    public function update(){
//        return Customer::where('id',$this->data['id'])->update($this->data);
//    }
*/

}
