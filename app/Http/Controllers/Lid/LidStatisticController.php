<?php


namespace App\Http\Controllers\Lid;


use App\Events\StatisticEvent;
use App\Http\Controllers\Controller;
use App\Models\Crm\Customer;
use App\Models\Crm\Lid;
//use App\Models\Mongo\LidStatistic;
use App\Models\Crm\LidStatistic;
use App\Models\MongoLog\Log;
use Carbon\Carbon;

class LidStatisticController extends Controller
{

    public $id;
    public $values;
    public $field;
    public $flag;
    public $manager;
    public $q;

    public function async(){
        $request = request()->all();
        $func = $request['func'];
        isset($request['id']) ? $this->id = $request['id'] : $this->id = '';
        isset($request['field']) ? $this->field = $request['field'] : $this->field = '';
        isset($request['flag']) ? $this->flag = $request['flag'] : $this->flag = '';
        isset($request['manager']) ? $this->manager = $request['manager'] : $this->manager = '';
        isset($request['values']) ? $this->validateValues($request['values']) : $this->values = '';
        isset($request['q']) ? $this->q = $request['q'] : $this->q = '';
        return call_user_func_array([__CLASS__,$func],[$this->values]);
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




    public function tt(){
        StatisticEvent::broadcast([
            'action' => 'tt',
            'row' => 'tt row'
        ]);
    }


    public function checkEditing(){
        $editing = LidStatistic::whereId($this->id)->first()->editing;
        if(is_null($editing) && $this->flag === 'open'){
             LidStatistic::whereId($this->id)->update(['editing' => $this->manager]);
             $state = LidStatistic::whereId($this->id)->first();
            StatisticEvent::broadcast([
                'action' => 'open',
                'row' => $state
            ]);
            return $state->editing;
        } elseif ($this->flag === 'close'){
            $state = LidStatistic::whereId($this->id)->update(['editing' => null]);
            StatisticEvent::broadcast([
                'action' => 'close',
                'row' => LidStatistic::whereId($this->id)->first()
            ]);
            return  $state;
        } else {
            return $editing;
        }
    }

    public function checkValue(){
        return LidStatistic::whereId($this->id)->get($this->field);
    }
    public function updateValue(){
        return LidStatistic::whereId($this->id)->update($this->values);
    }




    public function create(){
        $row = LidStatistic::create([
            'date'=> date("Y-m-d"),
            'time' => date("H:i"),
            'manager' => $this->manager,
            'call_status' => 9,
            'app_status' => 1,
        ]);
        StatisticEvent::broadcast([
            'action' => 'create',
            'row' => $row
        ]);
        return $row;
    }
    public function read(){
        return LidStatistic::with('lid','customer')->whereBetween('date',[$this->values['start'],$this->values['end']])
            ->orderBy('date','DESC')
            ->orderBy('time','DESC')->get();
    }

//->with(['events' => function($query) {
//    $query->where('finnish', 0)
//        ->orderBy('event_id', 'desc');
//}])


    /**
     * @return int
     */
    public function update(){
        $old = LidStatistic::where('id',$this->values['id'])->first();
        unset($this->values['lid']);
        unset($this->values['customer']);
        $this->values['editing'] = null;



        if(array_key_exists('call_status', $this->values) && $this->values['call_status'] == 7 && $old->call_status != 7){
            $this->values['date'] = date("Y-m-d");
            $this->values['time'] = date("H:i");
            $update = LidStatistic::where('id',$this->values['id'])->update($this->values);

            StatisticEvent::broadcast([
                'action' => 'order',
                'row' => LidStatistic::with('lid','customer')->where('id',$this->values['id'])->first()
            ]);

        } elseif (array_key_exists('call_status', $this->values) && $this->values['call_status'] != 7 && $old->call_status == 7) {
            $this->values['date'] = date("Y-m-d",strtotime($old->created_at));
            $this->values['time'] = date("H:i",strtotime($old->created_at));
            $update = LidStatistic::where('id',$this->values['id'])->update($this->values);

            StatisticEvent::broadcast([
                'action' => 'order',
                'row' => LidStatistic::with('lid','customer')->where('id',$this->values['id'])->first()
            ]);

        } else {
            $update = LidStatistic::where('id',$this->values['id'])->update($this->values);

            StatisticEvent::broadcast([
                'action' => 'update',
                'row' => LidStatistic::with('lid','customer')->where('id',$this->values['id'])->first()
            ]);

        }
        return $update;
    }

//    public function addCustomer(){
//
//    }








    public function updateField(){
        unset($this->values['lid']);
        unset($this->values['customer']);
        $this->values['editing'] = null;
        $update = LidStatistic::where('id',$this->values['id'])->update($this->values);

        StatisticEvent::broadcast([
            'action' => 'update',
            'row' => LidStatistic::where('id',$this->values['id'])->first()
        ]);
        return $update;
    }

    public function delete(){

        $row = LidStatistic::where('id',$this->id)->first();
        $log = Log::create([
            'model' => 'lid_statistics',
            'state' => 'delete',
            'id' => $this->id,
            'user' => \Admin::user()->id,
            'object' => $row->toArray(),
        ]);
        if($log->id){
            return LidStatistic::where('id',$this->id)->delete();
        } else {
            return $log;
        }
    }



    public function client(){
        $search = Customer::where('name','like', '%'.$this->q.'%')
            ->orWhere('organization','like', '%'.$this->q.'%')
            ->orWhere('phone','like', '%'.$this->q.'%')
            ->orWhere('email','like', '%'.$this->q.'%')
            ->orWhere('management_name','like', '%'.$this->q.'%')
            ->get();
        return $search;
    }

    public function findLid(){
        return Lid::where($this->values['key'],$this->values['val'])->get();
    }

    public function contract($type = false)
    {
        $st = Carbon::today()->toObject();
        $oLid = Lid::whereBetween('date_start', [Carbon::make($st->formatted), Carbon::make($st->formatted)->add(1, 'day')])
                ->get()->count() + 1;

        return substr( $st->year,2) . '-' . $st->month . '-' . $oLid . '-' . $st->day;

    }

    public function creteLid(){

        $sample = '{"id":{"$oid":"5e404588fe64ca5dca25a6c7"},"nr":{"$numberInt":"11"},"date":"15-03-2018","time":"16:00","site":{"$numberInt":"1"},"manager":{"$numberInt":"9999"},"call_status":{"$numberInt":"7"},"cause":"2-ка, клопы, гор и хол \nядреная доза, тяжелый случай","address":"\"\"\". г. Чехов, ул маркова, д 7 \n2 подъезд, кв 21, этаж 1\n\"","cash":"5300","cashless":null,"phone":"79099577191 89057523102","name":"Ирина","contract":"1114я","implement_date":"15-03-2018 25.03","customer":null,"refusal":null,"col":[{"$numberInt":"11"},"3","16.00","\"79099577191     Алексей\n89057523102 Ирина\"\"\"","Владимир","2-ка, клопы, гор и хол \nядреная доза, тяжелый случай","\"\"\". г. Чехов, ул маркова, д 7 \n2 подъезд, кв 21, этаж 1\n\"","5300",null,null,null,null,null,"1114я","25.03","заказ","1",null],"updated_at":{"$date":{"$numberLong":"1581363177000"}},"created_at":{"$date":{"$numberLong":"1581270408000"}}}';
        $arr=[];
        $cause = '';

        $arr['contract'] =  $this->contract();
        $arr['date_start'] =  Carbon::now()->toDateTimeString();
        if(isset($this->values['site'])) {$arr['site'] = $this->values['site'];};
        if(isset($this->values['manager'])) {$arr['manager_starter'] = $this->values['manager'];};


        if(isset($this->values['cause'])) {$cause .= ' Суть: '.$this->values['cause'];};
        if(isset($this->values['address'])) {$cause .= ' Адрес: '.$this->values['address'];};
        if(isset($this->values['cash'])) {$cause .= ' Нал: '.$this->values['cash'];};
        if(isset($this->values['cashless'])) {$cause .= ' Нал: '.$this->values['cashless'];};
        if(isset($this->values['cashless'])) {$cause .= ' Безнал: '.$this->values['cashless'];};
        if(isset($this->values['phone'])) {$cause .= ' Телефон: '.$this->values['phone'];};
        if(isset($this->values['name'])) {$cause .= ' Имя: '.$this->values['name'];};


        $arr['comment'] = $cause;


        $lid = Lid::create($arr);

         $update = LidStatistic::where('id',$this->values['id'])->update([
            'lid_id' => $lid['id'],
            'contract' => $lid['contract'],
            'date_start' => $lid['date_start'],
            'cost_date' => date("Y-m-d"),
            'call_status' => 7
        ]);


       StatisticEvent::broadcast([
            'action' => 'createLid',
            'row' => LidStatistic::with('lid','customer')->where('id',$this->values['id'])->first()
        ]);

//        return $update;



    }

        public function getStat(){
           return LidStatistic::all();
        }
        public function saveStat(){
            $ll =  LidStatistic::where('nr',$this->id)->where('date','')->where('site','')->where('manager','')->where('call_status','')->update($this->values);
            if($ll){
                return $this->id;
            }  else {
                return 'XXXXXXXX';
            }
        }

        public function parseCsv(){

            $csv = $_SERVER['DOCUMENT_ROOT'].'/csv/222222.csv';

            $c = explode('[',file_get_contents($csv));

            $d = [];
            $col = 1;
            foreach ($c as $k => $e) {//if($k < $this->values['limit'])
                $d[$k] = explode('~',$e);
                array_unshift($d[$k],$col);

                $ob = [
                    'nr' => $col,
                    'date' => '',
                    'time' => '',
                    'site' => '',
                    'manager' => '',
                    'call_status' => '',
                    'cause' => '',
                    'address' => '',
                    'cash' => '',
                    'cashless' => '',
                    'phone' => '',
                    'name' => '',
                    'contract' => '',
                    'implement_date' => '',
                    'customer' => '',
                    'refusal' => '',
                    'col' => $d[$k],
                ];

                LidStatistic::create($ob);



                $col++;

            }



            return $d;





        }



/*
        public function setStrato(){
//        $arDate = preg_replace('/[^0-9-]/', '', $this->values['date']);

        if(!mb_stristr($this->values['cause'], 'Суть')){
            $ob = [
                'nr' => $this->values['nr'],
                'date' => $this->values['date'],
                'time' => $this->values['time'],
                'site' => $this->values['site'],
                'manager' => $this->values['manager'] ? $this->values['manager'] : '9999',
                'call_status' => $this->values['call_status'],
                'cause' => $this->values['cause'],
                'address' => $this->values['address'],
                'cash' => $this->values['cash'],
                'cashless' => $this->values['cashless'],
                'phone' => $this->values['phone'],
                'name' => $this->values['name'],
                'contract' => $this->values['contract'],
                'implement_date' => $this->values['implement_date'],
                'customer' => $this->values['customer'],
                'refusal' => $this->values['refusal'],
                'col' => json_encode($this->values['col'],JSON_UNESCAPED_UNICODE,JSON_UNESCAPED_SLASHES),
            ];


            return \App\Models\Crm\LidStatistic::create($ob)->id;
        }





        }
*/


    // EVENTS
    public function editing(){
        return $this->id;
    }





}
