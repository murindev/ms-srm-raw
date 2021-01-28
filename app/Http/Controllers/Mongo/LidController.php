<?php


namespace App\Http\Controllers\Mongo;

use App\Http\Controllers\Controller;
use App\Models\Mongo\Lid;

class LidController extends Controller
{

    public $id;
    public $values;
    public $object;
    public function async(){
        $request = request()->all();
        $func = $request['func'];
        isset($request['id']) ? $this->id = $request['id'] : $this->id = '';
        isset($request['values']) ? $this->values = $request['values'] : $this->values = '';
        isset($request['object']) ? $this->object = $request['object'] : $this->object = '';
        return call_user_func_array([__CLASS__,$func],[$this->values]);
    }

    public function get(){
        return Lid::where('id',$this->id)->first();
    }


    public function create(){
//       return $this->object;
       return Lid::create($this->object);
    }
    public function update(){
        return Lid::where('id',$this->id)->update($this->object);
    }
    public function delete(){

    }


    public function sync(){
        if(Lid::where('id',$this->id)->count()){
            $this->update();
        } else {
            $this->create();
        }
    }

}
