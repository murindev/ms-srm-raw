<?php


namespace App\Http\Controllers\Printer;


use App\Http\Controllers\Controller;
use App\Http\Controllers\HelperController;
use App\Models\Crm\Act;
use App\Models\Crm\Customer;
use App\Models\Crm\Lid;

class PrinterController extends Controller
{
    public function getLid($id){
        return Lid::where('id',$id)->first();
    }
    public function getCustomer($id){
        return Customer::where('id',$id)->first();
    }
    public function getAct($id){
        return Act::with('volumes','implements')->where('id',$id)->first();
    }

    public function printSchedule(){
        $list = [];
        foreach (json_decode(request()->get('schedule')) as $item) {
            $list[] = array(
                'implement' => $item,
                'lid' => $this->getLid($item->lid_id),
                'customer' => $this->getCustomer($this->getLid($item->lid_id)->customer),
                'act' => $this->getAct($item->parent)
            );
        }
        return view('print.master_schedule',[
            'date' => request()->get('date'),
            'master' => request()->get('master'),
            'list' => $list,
            'h' => HelperController::helpers()
        ]);
    }
}
