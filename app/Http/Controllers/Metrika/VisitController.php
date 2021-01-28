<?php


namespace App\Http\Controllers\Metrika;


use App\Http\Controllers\Controller;
use App\Models\Metrika\Mossanexpert;
use App\Models\Metrika\Sesteam;
use Carbon\Carbon;

class VisitController extends Controller
{
    public $id;
    public $values;
    public $field;
    private $value;
    private $date;
    private $dateStart;
    private $dateEnd;
    private $base;


    /**
     * @return mixed
     */
    public function async(){
        $request = request()->all();
        $func = $request['func'];
        isset($request['start']) ? $this->dateStart = $request['start'] : $this->dateStart = '';
        isset($request['end']) ? $this->dateEnd = $request['end'] : $this->dateEnd = '';
        isset($request['base']) ? $this->base = $request['base'] : $this->base = '';



        isset($request['id']) ? $this->id = $request['id'] : $this->id = '';
        isset($request['value']) ? $this->value = $request['value'] : $this->value = '';
        isset($request['values']) ? $this->values : $this->values = '';
        isset($request['field']) ? $this->field = $request['field'] : $this->field = '';

        isset($request['date']) ? $this->date = $request['date'] : $this->date = '';

        return call_user_func_array([__CLASS__,$func],[$this->values]);
    }

    public function get(){
        $this->dateEnd = Carbon::make($this->dateEnd)->add(1, 'day')->toArray();
        if(strnatcmp($this->base,'sesteam') == 0){
            $tbl = Sesteam::whereBetween('date_human', [$this->dateStart, $this->dateEnd['formatted']])
//            ->orderBy('start_date')
                ->get();
        } else {
            $tbl = Mossanexpert::whereBetween('date_human', [$this->dateStart, $this->dateEnd['formatted']])
//            ->orderBy('start_date')
                ->get();
        }

        return $tbl;
    }
}
