<?php


namespace App\Http\Controllers\Logistic;


use App\Http\Controllers\Controller;
use App\Models\Crm\Act;
use App\Models\Crm\Implement;
use App\Models\Crm\Lid;
use Carbon\Carbon;
use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Support\Collection;

class MapController extends Controller
{


    public $implement;


    /**
     * @return mixed
     */
    public function async(){
        $request = request()->all();
        $func = $request['func'];
        unset($request['func']);
        return call_user_func_array([__CLASS__,$func],[$request]);
    }

    public function implementByDate($request){
       return  Implement::whereBetween('start_date', [Carbon::make($request['date']), Carbon::make($request['date'])->add(1, 'day')])
           ->orderBy('start_date')->get();
    }

    public function masters(){
       $masters =  Administrator::whereHas('roles', function ($q) {
            $q->where('role_id', 3); // role_start_dateid
        })->get(['id','name','username','color','text_color','city','avatar','phone','active'])->keyBy('id');

       return $masters;
    }

    public function schedule($request){
        $imp = $this->implementByDate($request);
        $byMaster = $imp->groupBy('master')->toArray();
        $byAct = $imp->groupBy('parent')->toArray();
        $byLid = $imp->groupBy('lid_id')->toArray();
        $acts = array_keys($byAct);
        $lidsID = array_keys($byLid);
        $lids = Lid::whereIn('id',$lidsID)->get()->keyBy('id');
        $acts = Act::with('volumes','implements')->whereIn('id',$acts)->get()->keyBy('id');
        $actsByID = $acts->groupBy('id')->toArray();
        $byGeo = $acts->map(function ($item, $key){
            $item['geo'] = $item['lat'].':'.$item['lon'];
            return $item;
        });

        return array(
            'masters' => $this->masters(),
            'byMasters' => $byMaster,
            'byAct' => $byAct,
            'byLid' => $byLid,
            'acts' => $acts,
            'lids' => $lids,
            'geo' => $byGeo->groupBy('geo')->toArray(),
        );

    }


    public function floating($request){
        $floating = Act::with('volumes','implements')->whereDate('floating_date_from','<=',$request['date'])
            ->whereDate('floating_date_to','>=',$request['date'])
            ->where('floating',1)
            ->where('finished','!=', 1)
            ->get();
        $map = $floating->map(function ($item, $key){
            $item['geo'] = $item['lat'].':'.$item['lon'];
            return $item;
        });
        return $map->groupBy('geo');
    }

    public function setMap($request) {
        /*
         * Получить дату
         * выбрать Выполнение по дате
         * превратить в акты
         * выбрать Плавающие по дате
         * превратить в акты
         * отформатировать акты по гео
         * */

        $floating = Act::with('volumes','implements.rules')->whereDate('floating_date_from','<=',$request['date'])
            ->whereDate('floating_date_to','>=',$request['date'])
            ->where('floating',1)
            ->where('finished','!=', 1)
            ->get();
        $implement = $this->implementByDate($request);
        $byMaster = $implement->groupBy('master');
        $actsID = array_keys($implement->groupBy('parent')->toArray());
        if($request['lid_act']) {
            $actsID[] = $request['lid_act'];
        }
        $acts = Act::with('volumes','implements.rules')->whereIn('id',$actsID)->get();
        $acts = $acts->map(function ($item){
            $item['scheduled'] = 1;
            return $item;
        });

        $collection = new Collection();
        $collection = $collection->merge($acts)->merge($floating);
        $collectionByID = $collection->sortBy('id')->keyBy('id');
        $collectionByLid = array_keys($collection->groupBy('parent')->toArray());
        $lids = Lid::whereIn('id',$collectionByLid)->get()->keyBy('id');


        $byGeo = $collectionByID->map(function ($item, $key){
            $item['geo'] = $item['lat'].':'.$item['lon'];
            return $item;
        });

        return array(
            'schedule' => $byMaster,
            'points' => $collectionByID,
            'markers' => $byGeo->groupBy('geo'),
            'lids' => $lids,
            'masters' => $this->masters(),
        );

    }



}
