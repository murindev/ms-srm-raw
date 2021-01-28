<?php


namespace App\Http\Controllers\Personal;


use App\Http\Controllers\Controller;

class Test extends Controller
{
    public $settings;
    public $values;
    public $user;
    public $id;

    public function async(){
        try {
            $user = auth()->userOrFail();

        } catch (\Tymon\JWTAuth\Exceptions\UserNotDefinedException $e) {
            // do something
            return 'dsfsdf' . $e;
        }
//        $request = request()->all();
//        $func = $request['func'];
//        isset($request['id']) ? $this->id = $request['id'] : $this->id = '';
//        isset($request['user']) ? $this->user = $request['user'] : $this->user = '';
//        isset($request['values']) ? $this->values = $request['values'] : $this->values = '';
//        return call_user_func_array([__CLASS__,$func],[$this->values]);
    }

    public function starts(){
        return 'Im a start';
    }

}
