<?php


namespace App\Http\Controllers\Log;

use App\Http\Controllers\Controller;

class LogController extends Controller
{
    public $id;
    public $values;

    public function async(){
        $request = request()->all();
        $func = $request['func'];
        isset($request['id']) ? $this->id = $request['id'] : $this->id = '';
        isset($request['values']) ? $this->values = $request['values'] : $this->values = '';

        return call_user_func_array([__CLASS__,$func],[$this->values]);
    }

    // LID
    public function getLidActions(){
        return 'test';
    }
    public function setLidActions(){
        return 'test';
    }
    public function updateLidActions(){
        return 'test';
    }
    public function getLidStatuses(){
        return 'test';
    }
    public function setLidStatuses(){
        return 'test';
    }
    public function updateLidStatuses(){
        return 'test';
    }

    // ACT

    public function getActActions(){
        return 'test';

    }
    public function setActActions(){
        return 'test';
    }
    public function updateActActions(){
        return 'test';
    }
    public function getActStatuses(){
        return 'test';
    }
    public function setActStatuses(){
        return 'test';
    }
    public function updateActStatuses(){
        return 'test';
    }
}

