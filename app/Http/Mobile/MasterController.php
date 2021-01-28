<?php


namespace App\Http\Mobile;


use App\Http\Controllers\Controller;
use App\Models\Crm\ActAuditPhoto;
use App\Models\Crm\ActExecution;
use App\Models\Crm\Implement;
use App\Models\Helper\Method;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MasterController extends Controller
{
    private $data;
    private $user;


    public function index()
    {

        $this->user = auth()->user();
        $this->data = \request()->get('data') ? \request()->get('data') : '';
        return call_user_func_array([__CLASS__, \request()->get('func')], [$this->data]);
    }

    public function implementsList()
    {
        return $this->byDate(request()->get('master'));
    }

    public function implementById()
    {
        $this->checkExecution(request()->get('id'));
        $res = '';
        request()->get('show')
            ? dump($this->byID(request()->get('id'))->toArray())
            : $res = $this->byID(request()->get('id'));
        return $res;
    }

    public function onStepDeparture(){
        try {
            ActExecution::where('act_id', request()->get('act_id'))->update(['departured' => now(),'step' => 1]);
        } catch  (\Exception $er) {
            return $er;
        }
        return $this->byID(request()->get('id'));
    }

    public function onStepArrived(){
        try {
            ActExecution::where('act_id', request()->get('act_id'))->update(['arrived' => now(),'step' => 2]);
        } catch  (\Exception $er) {
            return $er;
        }
        return $this->byID(request()->get('id'));
    }

    public function onStepAudit(){
        try {
            ActExecution::where('act_id', request()->get('act_id'))->update(['audit' => now(),'step' => 3]);
        } catch  (\Exception $er) {
            return $er;
        }
        return $this->byID(request()->get('id'));
    }

    public function onAuditPhotoSave(){
        $file = request()->file('file');
        $file->move(public_path() . "/storage/audit/photo/", $file->getClientOriginalName());
        $implement = $this->byID(request()->get('implement_id'));
        ActAuditPhoto::create(['act_id' => $implement->parent,'photo' => $file->getClientOriginalName()]);
    }

    public function onContractFileSave(){
        $file = request()->file('file');
        $file->move(public_path() . "/storage/audit/act/", $file->getClientOriginalName());
        $implement = $this->byID(request()->get('implement_id'));
        ActExecution::whereActId($implement->parent)->update(['contract' => $file->getClientOriginalName(),'step' => 4]);
    }


    public function onRecordSave(){
        $file = request()->file('file');
        $file->move(public_path() . "/storage/audit/record/", $file->getClientOriginalName());
        $implement = $this->byID(request()->get('implement_id'));
        ActExecution::whereActId($implement->parent)->update(['record_file' => $file->getClientOriginalName()]);
    }
    public function onStepFinished(){
        try {
            ActExecution::where('act_id', request()->get('act_id'))->update(['finished' => now(),'step' => 5]);
        } catch  (\Exception $er) {
            return $er;
        }
        return $this->byID(request()->get('id'));
    }

    public function implementsByMonth(){
       return Implement::with($this->with)->where('master',request()->get('masterId'))
            ->whereBetween('start_date', [Carbon::make(request()->get('firstDate')), Carbon::make(request()->get('lastDate'))])
           ->orderBy('start_date')
            ->get();
//        return request()->toArray();
    }





    // helper functions

    public $with = ['act.execution','act.auditPhotos', 'act.volumesMobile.squareEntityRow', 'act.volumesMobile.pestRow'];


    public function byDate($master, $date = null)
    {
        $date = request()->get('date') != null ? request()->get('date') : date("Y-m-d");
        $implementList = Implement::with($this->with)
            ->whereBetween('start_date', [Carbon::make($date), Carbon::make($date)->add(1, 'day')])
            ->where('master', request()->get('master'))->orderBy('start_date');
        return $implementList->get();
    }


    public function byID($id)
    {
        $implement = Implement::with($this->with)->where('id', $id);
        return $implement->first();
    }

    public function checkExecution($implementId){
        $implement = $this->byID($implementId);
        ActExecution::firstOrCreate(['act_id' => $implement->parent]);
    }






}
