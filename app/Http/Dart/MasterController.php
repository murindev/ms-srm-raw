<?php

namespace App\Http\Dart;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Crm\Implement;
use Carbon\Carbon;
use DB;

class MasterController extends Controller
{
    private $data ;
    private $user;

    public function index()
    {
        $this->user = auth()->user();
        $this->data = \request()->get('data') ? \request()->get('data') : '';
        return call_user_func_array([__CLASS__, \request()->get('func')], [$this->data]);
    }
/* Master section */
    public function implement(){
        return $this->user->id;
        // return Implement::whereMaster(request()->get('master_id'))
        // ->whereBetween('start_date', [Carbon::make($this->date), Carbon::make($this->date)->add(1, 'day')])
        // ->orderBy('start_date')->get();
    }

    public function open()
    {
        $data = "This data is open and can be accessed without the client being authenticated";
        return response()->json(compact('data'),200);

    }

    public function closed()
    {
        $data = "Only authorized users can see this";
        return response()->json(compact('data'),200);
    }
}
