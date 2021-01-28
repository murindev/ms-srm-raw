<?php


namespace App\Http\Controllers\Personal;


use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Controller;
use App\Admin\Controllers\AuthController;
use App\Models\Admin\AdminUser;
use App\Providers\MobileUserProvider;
use Barryvdh\Debugbar\Facade;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Middleware\Authenticate;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Support\Facades\Auth;

class MasterController extends Controller
{
    public $settings;
    public $values;
    public $user;
    public $id;

    public function async(){
        $request = request()->all();
        $func = $request['func'];
        isset($request['id']) ? $this->id = $request['id'] : $this->id = '';
        isset($request['user']) ? $this->user = $request['user'] : $this->user = '';
        isset($request['values']) ? $this->values = $request['values'] : $this->values = '';
        return call_user_func_array([__CLASS__,$func],[$this->values]);
    }

    public function test()
    {
//        $rr = new MobileUserProvider();
        $cre = array(
            'username' => 'admin',
            'password' => 'Uretr@l777',

        );
//        $gr = new Authenticatable;

        // $rr->retrieveByCredentials($cre)
//        $d = \Auth::attempt($cre);

//        dump(\Auth::logout());
//        dump(\Auth::loginUsingId(1,true));
//        dump(\Auth::attempt($cre,true));
//        \Auth::loginUsingId(1,true);
//        dump('viaRemember',\Auth::viaRemember());
        dump('check',\Auth::check());
        dump('getName',\Auth::getName());
        $fg = \Auth::user();
        dump('user',$fg);

        $ii =  bcrypt ($fg->remember_token);
        dump('$ii',$ii);

/*        $ry = AdminUser::all();
        dump($ry);*/

//$ff = new LoginController();
//dump($ff);


        return 'this is test';
    }

}
