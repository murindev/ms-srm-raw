<?php


namespace App\Http\Controllers;


use App\Models\Settings;

class SettingsController extends Controller
{
    public $type;
    public $settings;
    public $manager;

    public function async(){
        $request = request()->all();
        $func = $request['func'];
        isset($request['type']) ? $this->type = $request['type'] : $this->type = '';
        isset($request['manager']) ? $this->manager = $request['manager'] : $this->manager = '';
        isset($request['settings']) ? $this->settings = $request['settings'] : $this->settings = [];
        return call_user_func_array([__CLASS__,$func],[$request]);
    }


    public function create(){
        Settings::create([
            'type' => $this->type,
            'manager' => $this->manager,
            'settings' => $this->settings
        ]);
    }
    public function read(){
        $res = Settings::where('manager',$this->manager)->get();
        return $res->keyBy('type');
    }
    public function update(){
        $res = Settings::where('manager',$this->manager)->where('type',$this->type)->first();
        if (!$res) {
            $this->create();
        }
        Settings::where('manager',$this->manager)->where('type',$this->type)->update(['settings' => $this->settings]);
        return $this->read();
    }

}
