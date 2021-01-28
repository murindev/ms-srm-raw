<?php


namespace App\Http\Vuex;


use App\Http\Controllers\Controller;
use App\Models\Crm\Act;
use App\Events\Act as Event;
use App\Models\Crm\Volume;

class ActVuex extends Controller
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
        where table_schema = ? and table_name = ? ', ['crm2', ' acts']
        );
        return collect($results)->pluck('column_default', 'column_name');
    }

    private function create()
    {
        return Act::create($this->data);
    }

    private function read()
    {
        return Act::where($this->data['field'], $this->data['value'])->get();
    }

    private function get()
    {
        return Act::where('id', $this->data['id'])->first();
    }

    private function update()
    {
        return Act::where('id', $this->data['id'])->update($this->data);
    }

    private function delete()
    {
        return Act::where('id', $this->data['id'])->delete();
    }

    public function search()
    {
        $r = new Act();
        $fields = $r->searchable;
        return $r::where(function ($query) use ($fields) {
            foreach ($fields as $field) {
                $query->orWhere($field, 'like', '%' . $this->data . '%');
            }
        })->get();
    }

    public function searchField()
    {
        return Act::where($this->data['field'], $this->data['value'])->get();
    }

// eof base
    public function updateRow()
    {
        $this->update();
        $act =  Act::with('volumes')->where('id', $this->data['id'])->first();
        Event::broadcast(['action' => 'update', 'data' => $act]);
        return $act;

    }

    public function fetchSearchRow()
    {
        return Act::with('volumes')->where($this->data['field'], $this->data['value'])->first();
    }

    public function eCreateRow()
    {
        Event::broadcast(['action' => 'create', 'data' => $this->create()]);
    }

    public function eCreateRowWithVolumes()
    {
        $cnt = Volume::where('lid_id', $this->data['parent'])->count();
        $this->data['act_nr'] = $cnt + 1;
        $volumes = $this->data['volumes'];
        unset($this->data['volumes']);
        $act = $this->create();

        if ($act) {
            foreach ($volumes as $k => $volume) {
                Volume::whereId($volume['id'])->update([
                    'parent' => $act->id,
                    'lid_id' => $act->parent,
                    'statistic_id' => $act->statistic_id,

                ]);
            }
            Event::broadcast(['action' => 'create_with_volumes', 'data' => $act]);
        } else {
            Event::broadcast(['action' => 'create_with_volumes', 'data' => false]);
        }


    }

    public function eUpdateRow()
    {
        $this->update();
        Event::broadcast(['action' => 'update', 'data' => $this->get()]);
    }
//    public function update(){
//        return Customer::where('id',$this->data['id'])->update($this->data);
//    }


}
