<?php


namespace App\Http\Controllers;
use App\Models\Admin\AdminUser;
use App\Models\Helper\Action;
use App\Models\Helper\Company;
use App\Models\Helper\CustomerType;
use App\Models\Helper\Drug;
use App\Models\Helper\Equipment;
use App\Models\Helper\Method;
use App\Models\Helper\PaymentCondition;
use App\Models\Helper\PaymentRule;
use App\Models\Helper\Pest;
use App\Models\Helper\Phone;
use App\Models\Helper\Region;
use App\Models\Helper\Rules;
use App\Models\Helper\Servicing;
use App\Models\Helper\Square;
use App\Models\Helper\Status;
use App\Models\Helper\Tools;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\Role;
use http\Env\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HelperController
{
    public $helpers = [];
    public $manager;
    public $user;
    public $users;

    public function init(){

    }

    public static function usersList(){
/*        $arrUsers = AdminUser::all()->keyBy('id')->toArray();
        $users = [];
        foreach ($arrUsers as $k => $u){
            unset($u['password']);
            unset($u['remember_token']);
            $users[$k] = $u;
        }*/
        $users = DB::table('admin_users')
            ->leftJoin('admin_role_users', 'admin_users.id', '=', 'admin_role_users.user_id')
            ->select('id','username','name','color','shift','text_color','city','avatar','phone','active','role_id','salary','percent','percent_limit','timeline')
            ->get()->keyBy('id')->toArray();
        $users[0] = [
            'active' => 0,
            'avatar' => null,
            'city' => 495,
            'color' => '#ff0000',
            'text_color' => '#000',
            'name' => 'demo user',
            'role_id' => 1,
            'salary' => 50000,
            'timeline' => 5,
            'shift' => 1
        ];
        return  $users;
    }
    public static function usersListArray(){
        /*        $arrUsers = AdminUser::all()->keyBy('id')->toArray();
                $users = [];
                foreach ($arrUsers as $k => $u){
                    unset($u['password']);
                    unset($u['remember_token']);
                    $users[$k] = $u;
                }*/
        $users = DB::table('admin_users')
            ->leftJoin('admin_role_users', 'admin_users.id', '=', 'admin_role_users.user_id')
            ->select('id','username','name','color','shift','text_color','city','avatar','phone','active','role_id','salary','percent','percent_limit','timeline')
            ->get();
        $users[0] = [
            'id' => 0,
            'active' => 0,
            'avatar' => null,
            'city' => 495,
            'color' => '#ff0000',
            'text_color' => '#000',
            'name' => 'demo user',
            'role_id' => 1,
            'salary' => 50000,
            'timeline' => 5,
            'shift' => 1
        ];
        return  $users;
    }

    public static function groups(){
        $users = DB::table('admin_users as A')
            ->select('A.id','A.username','A.name','A.color','A.shift','A.text_color','A.city','A.avatar','A.phone','A.active','B.role_id','A.salary','A.percent','A.percent_limit','A.timeline')

//        ->addSelect(DB::raw('SUM(CASE WHEN B.approve = 1 ELSE 0 END) as Yshow'))
            ->leftjoin('admin_role_users as B', function($join) {
                $join->on('A.id', '=', 'B.user_id');
            })
            ->get()->toArray();

        $usersGr = [];
        foreach ($users as $u){
            if(isset($usersGr[$u->id])) {
                array_push($usersGr[$u->id]->groups,$u->role_id);
            } else {
                $usersGr[$u->id] = $u;
                $usersGr[$u->id]->groups = [ $u->role_id];
            }
        }
        return $usersGr;
    }

    public static function usersListByRole($role){
        /*        $arrUsers = AdminUser::all()->keyBy('id')->toArray();
                $users = [];
                foreach ($arrUsers as $k => $u){
                    unset($u['password']);
                    unset($u['remember_token']);
                    $users[$k] = $u;
                }*/
        $users = DB::table('admin_users')
            ->leftJoin('admin_role_users', 'admin_users.id', '=', 'admin_role_users.user_id')
            ->select('id','username','name','color','shift','text_color','city','avatar','phone','active','role_id','salary','percent','percent_limit','timeline')
            ->get()->keyBy('id')->toArray();
        $users[0] = [
            'active' => 0,
            'avatar' => null,
            'city' => 495,
            'color' => '#ff0000',
            'text_color' => '#000',
            'name' => 'demo user',
            'role_id' => 1,
            'salary' => 50000,
            'timeline' => 5,
            'shift' => 1
        ];
        return  $users;
    }
    public static function manager(){
        $user = config('const.admin')->user()->toArray();
        $userList = self::usersList();

        return $userList[$user['id']];
    }


    public function links($val){
        return array(
            'lid' => '/layout/lid/407/edit'
        );
    }


    public static function helpers(){
        $h = [];
        $h['user'] = self::manager();
//        $h['users'] = self::usersList();
        $h['users'] = self::groups();
        $h['roles'] = Role::all()->pluck('slug','id');
        $h['roles_obj'] = Role::all()->keyBy('id')->toArray();
        $h['actions'] = Action::all()->keyBy('id')->toArray();
        $h['companies'] =  Company::all()->keyBy('id')->toArray();
        $h['drugs'] = Drug::all()->keyBy('id')->toArray();
        $h['equipments'] = Equipment::all()->keyBy('id')->toArray();
        $h['methods'] = Method::all()->keyBy('id')->toArray();
        $h['methods_asc'] = Method::orderBy('name','asc')->get();
        $h['pests'] = Pest::all()->keyBy('id')->toArray();
        $h['pests_asc'] = Pest::orderBy('name','asc')->get();
        $h['phones'] = Phone::all()->keyBy('id')->toArray();
        $h['regions'] = Region::all()->keyBy('id')->toArray();
        $h['servicing'] = Servicing::all()->keyBy('id')->toArray();
        $h['square'] = Square::all()->keyBy('id')->toArray();
        $h['statuses'] = Status::all()->keyBy('id')->toArray();
        $h['tools'] = Tools::all()->keyBy('id')->toArray();
        $h['customer'] = CustomerType::all()->keyBy('id')->toArray();
        $h['payment_rules'] = PaymentRule::all()->keyBy('id')->toArray();
        $h['payment_conditions'] = PaymentCondition::all()->keyBy('id')->toArray();
        $h['rules'] = Rules::all()->sortBy('sort')->groupBy('role');
        return $h;
    }

    public static function arrHelpers(){
        $h = [];
        $h['user'] = self::manager();
        $h['users'] = self::usersListArray();
        $h['roles'] = Role::all();
        $h['actions'] = Action::all();
        $h['companies'] =  Company::all();
        $h['drugs'] = Drug::all();
        $h['equipments'] = Equipment::all();
        $h['methods'] = Method::all();
//        $h['pests'] = Pest::all();
        $h['pests'] = Pest::orderBy('name','asc')->get();
        $h['pests_asc'] = Pest::orderBy('name','asc')->get();
        $h['phones'] = Phone::all();
        $h['regions'] = Region::all();
        $h['servicing'] = Servicing::all();
        $h['square'] = Square::all();
        $h['statuses'] = Status::all();
        $h['tools'] = Tools::all();
        $h['customer'] = CustomerType::all();
        $h['payment_rules'] = PaymentRule::all();
        $h['payment_conditions'] = PaymentCondition::all();
        $h['rules'] = Rules::all();
        return $h;
    }

    public static function customHelpers(\Request $request){
        $h = [];

        if(in_array('user',$request['entities'])) {
            $h['user'] = self::manager();
        }
        if(in_array('users',$request['entities'])) {
            $h['staff'] = self::usersList();
        }
//        if(in_array('',$request['entities'])) {
//
//        }


/*        $h['roles'] = Role::all()->pluck('slug','id');
        $h['roles_obj'] = Role::all()->keyBy('id')->toArray();
        $h['actions'] = Action::all()->keyBy('id')->toArray();
        $h['companies'] =  Company::all()->keyBy('id')->toArray();
        $h['drugs'] = Drug::all()->keyBy('id')->toArray();
        $h['equipments'] = Equipment::all()->keyBy('id')->toArray();
        $h['methods'] = Method::all()->keyBy('id')->toArray();
        $h['pests'] = Pest::all()->keyBy('id')->toArray();
        $h['phones'] = Phone::all()->keyBy('id')->toArray();
        $h['regions'] = Region::all()->keyBy('id')->toArray();
        $h['servicing'] = Servicing::all()->keyBy('id')->toArray();
        $h['square'] = Square::all()->keyBy('id')->toArray();
        $h['statuses'] = Status::all()->keyBy('id')->toArray();
        $h['tools'] = Tools::all()->keyBy('id')->toArray();
        $h['customer'] = CustomerType::all()->keyBy('id')->toArray();
        $h['payment_rules'] = PaymentRule::all()->keyBy('id')->toArray();
        $h['payment_conditions'] = PaymentCondition::all()->keyBy('id')->toArray();
        $h['rules'] = Rules::all()->sortBy('sort')->groupBy('role');*/
        return $h;
    }



    public function async(){
        return call_user_func_array([__CLASS__,request()->get('func')],[request()->all()]);
    }

    public function getSchema(){
        return Schema::getColumnListing(request()->get('table'));
    }


}
