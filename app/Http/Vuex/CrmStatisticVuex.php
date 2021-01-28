<?php


namespace App\Http\Vuex;

use App\Events\CrmStatistic;
use App\Http\Controllers\Controller;
use App\Models\Crm\Lid;
use App\Models\Crm\LidStatistic;
use App\Events\CrmStatistic as Event;
use Spatie\QueryBuilder\QueryBuilder;

class CrmStatisticVuex extends Controller
{
    private $id;
    private $data;
    private $relations = ['lid','customer','act','volumes','finance'];



    public function index()
    {
        $this->id = \request()->get('id') ? \request()->get('id') : '';
        $this->data = \request()->get('data') ? \request()->get('data') : '' ;
        return call_user_func_array([__CLASS__,\request()->get('func')],[\request()->all()]);
    }
// base
    public function columns()
    {
        $results = \DB::select('
            select column_name, column_default from information_schema.columns
            where table_schema = ? and table_name = ? ', ['crm2', ' lid_statistics']
        );
        return collect($results)->pluck('column_default','column_name');
    }
    private function create(){
        return LidStatistic::create($this->data);
    }
    public function init(){
        return LidStatistic::with($this->relations)->whereBetween('date',[$this->data['start'],$this->data['end']])
            ->orderBy('date','DESC')
            ->orderBy('time','DESC')->get();
    }
    private function get(){
        return LidStatistic::with($this->relations)->where('id', $this->data['id'])->first();
    }
    private function update(){
        return LidStatistic::where('id',$this->data['id'])->update($this->data);
    }
    private function delete() {
        return LidStatistic::where('id',$this->data['id'])->delete();
    }
    public function search(){
/*        $r = new LidStatistic();
        $fields = $r->searchable;
        return $r::where(function ($query) use ($fields){
            foreach ($fields as $field){
                $query->orWhere($field, 'like','%'.$this->data.'%');
            }
        })->get();*/
    }

    public function read(){
        return LidStatistic::with($this->relations)->whereBetween('date',[$this->data['start'],$this->data['end']])
            ->orderBy('date','DESC')
            ->orderBy('time','DESC')->get();
    }


    public function searchField(){
        return LidStatistic::where($this->data['field'],$this->data['value'])->get();
    }






// vuex
    public function updateRow(){
        $this->update();
        $res = $this->get();
        Event::broadcast(['action' => 'update_row', 'data' => $res])->toOthers();
        return $res;
    }

    public function newLid(){
//        return [
//            'data' => $this->data,
//            'id' => $this->id,
//        ];
        $lid = Lid::create($this->data);
        if($lid){
            LidStatistic::whereId($this->id)->update(['lid_id' => $lid->id,'customer_id' => $lid->customer]);
            $res = LidStatistic::with($this->relations)->whereId($this->id)->first();
            Event::broadcast([
                'action' => 'update_row_new_lid',
                'data' => $res
            ])->toOthers();
            return $res;
        } else {
            return 'hhh';
        }

    }



    public function updateRowComplex(){
        $old = LidStatistic::where('id',$this->data['id'])->first();
        unset($this->data['lid']);
        unset($this->data['customer']);
        $this->data['editing'] = null;



        if(array_key_exists('call_status', $this->data) && $this->data['call_status'] == 7 && $old->call_status != 7){
            $this->data['date'] = date("Y-m-d");
            $this->data['time'] = date("H:i");
            $update = LidStatistic::where('id',$this->data['id'])->update($this->data);

            Event::broadcast([
                'action' => 'order',
                'row' => LidStatistic::with('lid','customer')->where('id',$this->data['id'])->first()
            ])->toOthers();

        } elseif (array_key_exists('call_status', $this->data) && $this->data['call_status'] != 7 && $old->call_status == 7) {
            $this->data['date'] = date("Y-m-d",strtotime($old->created_at));
            $this->data['time'] = date("H:i",strtotime($old->created_at));
            $update = LidStatistic::where('id',$this->data['id'])->update($this->data);

            Event::broadcast([
                'action' => 'order',
                'row' => LidStatistic::with('lid','customer')->where('id',$this->data['id'])->first()
            ])->toOthers();

        } else {
            $update = LidStatistic::where('id',$this->data['id'])->update($this->data);

            Event::broadcast([
                'action' => 'update',
                'row' => LidStatistic::with('lid','customer')->where('id',$this->data['id'])->first()
            ])->toOthers();

        }
        return $update;
    }





//////////////////////////
/// C


    public function fetchRow(){
        return $this->get();
    }
    /**
     * @return LidStatistic|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */

/// E

    public function eUpdateField(){
        $update = LidStatistic::where('id',$this->data['id'])->update([$this->data['field'] => $this->data['value']]);
        if($update){
            $res = LidStatistic::with('lid','customer','act')->where('id',$this->data['id'])->first();
            Event::broadcast(['action' => 'update', 'data' => $res])->toOthers();
            return $res;
        }
    }


    public function eCreateRow(){
        Event::broadcast([
            'action' => 'create',
            'data' => $this->create()
        ])->toOthers();
    }
    public function eUpdateRow(){
        $this->update();
        Event::broadcast(['action' => 'update', 'data' => $this->get()])->toOthers();
    }

    public function initTableRow(){
        $this->update();
        Event::broadcast(['action' => 'update', 'data' => $this->get()])->toOthers();
        return $this->get();
    }

}
