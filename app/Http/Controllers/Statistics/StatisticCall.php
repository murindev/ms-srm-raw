<?php


namespace App\Http\Controllers\Statistics;


use App\Http\Controllers\Controller;
use App\Models\Crm\Act;
use App\Models\Crm\Lid;
use App\Models\Crm\LidStatistic;
use function React\Promise\map;

class StatisticCall extends Controller
{

    private $start;
    private $end;

    private $lidsWithActs;
    private $acts;
    private $stat;

    public $byLid = [];
    public $byStat = [];

    public $byFinActivity = [];
    public $byFin = [];


    public $managers;

    public $period = null;


    public function __construct()
    {
        $this->period = request()->get('period') ? [request()->get('period')['start'], request()->get('period')['end']] : null;
//        $this->period = ['2020-09-01','2020-10-01'];

        $this->start = $this->microtime_float();
        $this->lidsWithActs = Lid::with('client', 'acts.volume', 'acts.implementFirst')->get();

        $stat = LidStatistic::with(['customer']);
        $this->period && $stat->whereBetween('date', $this->period);
        $this->stat = $stat->get();


        $managers = LidStatistic::selectRaw('distinct(manager)');
        $this->period && $managers->whereBetween('date', $this->period);
        $this->managers = $managers->pluck('manager');

        $this->byLid();
        $this->byStat();

        $this->end = $this->microtime_float();
    }


    public function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

    public function index()
    {
        if (request()->get('real')) {
            return json_encode($this);
        } else {
            $time = $this->end - $this->start;
            dump($time);
            dump($this->byLid);
            dump($this->byStat);
        }


    }

    public function byLid()
    {
        $this->byLid['lidOrderSource'] = [];
        $this->byLid['lidServicing'] = [];
        $this->byLid['lidPaymentSystem'] = [];
        $this->byLid['lidCustomerStatus'] = [];
        $this->byLid['actPropertyTypes'] = [];
        $this->byLid['actVolumePest'] = [];
        $this->byLid['actVolumeSquareEntities'] = [];
        $this->byLid['actVolumeMethods'] = [
            'common' => [],
            'byManager' => []
        ];
        $this->byLid['actVolumePrices'] = [
            'common' => [
                'cashSum' => 0,
                'cashlessSum' => 0
            ],
            'byManager' => []
        ];
        $this->byLid['actVolumeFinishedPrices'] = [
            'common' => [
                'cashSum' => 0,
                'cashlessSum' => 0
            ],
            'byManager' => []
        ];


        foreach ($this->lidsWithActs as $lidsWithAct) {
            $this->lidEntityCountCommon($lidsWithAct, $lidsWithAct->site, 'lidOrderSource');
            $this->lidEntityCountCommon($lidsWithAct, $lidsWithAct->servicing, 'lidServicing');
            $this->lidEntityCountCommon($lidsWithAct, $lidsWithAct->payment_system, 'lidPaymentSystem');
            $this->lidEntityCountCommon($lidsWithAct, $lidsWithAct->client['status'], 'lidCustomerStatus');
//                    $this->actsLoop($lidsWithAct);
        }

// ACTS

        $acts = Act::with(['volume', 'implementFirst'])->whereBetween('created_at',$this->period)->get();

        foreach ($acts as $act) {
            $this->actPropertyTypes($act);
            $this->actVolumePest($act);
        }
    }

    public function lidEntityCountCommon($lidsWithAct, $entityKey, $entity)
    {
        $key = $entityKey ? $entityKey : 'null';
        if (array_key_exists($key, $this->byLid[$entity])) {
            $this->byLid[$entity][$key]++;
        } else {
            $this->byLid[$entity][$key] = 1;
        }
    }

    public function actPropertyTypes($act)
    {
        $creatorKey = $act->creator ? $act->creator : 'null';
        $propertyTypeKey = $act->property_type ? $act->property_type : 'null';
        if (!array_key_exists($creatorKey, $this->byLid['actPropertyTypes'])) {
            $this->byLid['actPropertyTypes'][$creatorKey] = [];
        }

        if (array_key_exists($propertyTypeKey, $this->byLid['actPropertyTypes'][$creatorKey])) {
            $this->byLid['actPropertyTypes'][$creatorKey][$propertyTypeKey]++;
        } else {
            $this->byLid['actPropertyTypes'][$creatorKey][$propertyTypeKey] = 1;
        }
    }

    public function actVolumePest($act)
    {
        $creatorKey = $act->creator ? $act->creator : 'null';
        if (!array_key_exists($creatorKey, $this->byLid['actVolumePest'])) {
            $this->byLid['actVolumePest'][$creatorKey] = [];
        }
        if (!array_key_exists($creatorKey, $this->byLid['actVolumeSquareEntities'])) {
            $this->byLid['actVolumeSquareEntities'][$creatorKey] = [];
        }
// actVolumeMethods
        if (!array_key_exists($creatorKey, $this->byLid['actVolumeMethods']['byManager'])) {
            $this->byLid['actVolumeMethods']['byManager'][$creatorKey] = [];
        }


        foreach ($act->volume as $volume) {
            $volumePest = $volume->pest ? $volume->pest : 'null';
            $volumeEntity = $volume->entity ? $volume->entity : 'null';

            if (array_key_exists($volumePest, $this->byLid['actVolumePest'][$creatorKey])) {
                $this->byLid['actVolumePest'][$creatorKey][$volumePest]++;
            } else {
                $this->byLid['actVolumePest'][$creatorKey][$volumePest] = 1;
            }

            if (array_key_exists($volumeEntity, $this->byLid['actVolumeSquareEntities'][$creatorKey])) {
                $this->byLid['actVolumeSquareEntities'][$creatorKey][$volumeEntity]++;
            } else {
                $this->byLid['actVolumeSquareEntities'][$creatorKey][$volumeEntity] = 1;
            }

            foreach ($volume->method as $method) {


                if (array_key_exists($method, $this->byLid['actVolumeMethods']['common'])) {
                    $this->byLid['actVolumeMethods']['common'][$method]++;
                } else {
                    $this->byLid['actVolumeMethods']['common'][$method] = 1;
                }

                if (array_key_exists($method, $this->byLid['actVolumeMethods']['byManager'][$creatorKey])) {
                    $this->byLid['actVolumeMethods']['byManager'][$creatorKey][$method]++;
                } else {
                    $this->byLid['actVolumeMethods']['byManager'][$creatorKey][$method] = 1;
                }
            }
            $this->sumPrices($creatorKey, 'actVolumePrices', $volume);
            if ($act->finished == 1) {
                $this->sumPrices($creatorKey, 'actVolumeFinishedPrices', $volume);
            }


        }

    }

    public function sumPrices($manager, $entity, $volume)
    {

        $this->byLid[$entity]['common']['cashSum'] += $volume->price_fact;
        $this->byLid[$entity]['common']['cashlessSum'] += $volume->price_standard;

        if (!array_key_exists($manager, $this->byLid[$entity]['byManager'])) {
            $this->byLid[$entity]['byManager'][$manager] = [
                'cashSum' => 0,
                'cashlessSum' => 0
            ];
        }

        if (array_key_exists($manager, $this->byLid[$entity]['byManager'])) {
            $this->byLid[$entity]['byManager'][$manager]['cashSum'] += $volume->price_fact;
            $this->byLid[$entity]['byManager'][$manager]['cashlessSum'] += $volume->price_standard;
        }
    }

    public function byStat()
    {
        $this->byStat = [
            'callStatuses' => [
                'common' => [],
                'byManagers' => [],
            ],
            'appStatuses' => [
                'common' => [],
                'commonOrdered' => [],
                'byManagers' => [],
                'byManagersOrdered' => [],
            ],
            'customer' => [
                'common' => [],
                'commonOrdered' => [],
                'byManagers' => [],
                'byManagersOrdered' => [],
            ],
            'sources' => [
                'common' => [],
                'byManagers' => [],
            ]
        ];

        foreach ($this->stat as $stat) {
            $managerKey = $stat->manager ? $stat->manager : 'null';
            $this->statEntityCount($stat->call_status ? $stat->call_status : 'null', 'callStatuses', $managerKey);
            $this->statEntityCount($stat->app_status ? $stat->app_status : 'null', 'appStatuses', $managerKey, $stat);
            $this->statEntityCount(
                $stat->getRelation('customer') && $stat->getRelation('customer')->status ? $stat->getRelation('customer')->status : 3,
                'customer', $managerKey, $stat
            );
            $this->statEntityCount($stat->site ? $stat->site : 'null', 'sources', $managerKey);
        }
    }

    public function statEntityCount($entityKey, $entity, $managerKey, $row = null)
    {
        array_key_exists($entityKey, $this->byStat[$entity]['common'])
            ? $this->byStat[$entity]['common'][$entityKey]++
            : $this->byStat[$entity]['common'][$entityKey] = 1;

        if ($row && array_key_exists($entityKey, $this->byStat[$entity]['commonOrdered']) && $row->call_status == 7) {

            $this->byStat[$entity]['commonOrdered'][$entityKey]++;
        } elseif ($row && $row->call_status == 7) {
            $this->byStat[$entity]['commonOrdered'][$entityKey] = 1;
        }


        if (!array_key_exists($managerKey, $this->byStat[$entity]['byManagers'])) {
            $this->byStat[$entity]['byManagers'][$managerKey] = [];
            $this->byStat[$entity]['byManagersOrdered'][$managerKey] = [];
        }

        array_key_exists($entityKey, $this->byStat[$entity]['byManagers'][$managerKey])
            ? $this->byStat[$entity]['byManagers'][$managerKey][$entityKey]++
            : $this->byStat[$entity]['byManagers'][$managerKey][$entityKey] = 1;

        if ($row && array_key_exists($entityKey, $this->byStat[$entity]['byManagersOrdered'][$managerKey]) && $row->call_status == 7) {
            $this->byStat[$entity]['byManagersOrdered'][$managerKey][$entityKey]++;
        } elseif ($row && $row->call_status == 7) {
            $this->byStat[$entity]['byManagersOrdered'][$managerKey][$entityKey] = 1;
        }
    }


}
