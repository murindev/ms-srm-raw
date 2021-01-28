<?php

namespace App\Models\Crm;

use App\Models\Finance\Finance;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Crm\Lid
 *
 * @property int $id
 * @property string|null $manager
 * @property int|null $site
 * @property int|null $manager_starter
 * @property \App\Models\Crm\Customer $customer
 * @property string|null $contract
 * @property int|null $contract_transferred
 * @property int $contract_signed
 * @property string|null $contract_file
 * @property int|null $servicing
 * @property int|null $action
 * @property string|null $date_start
 * @property string|null $action_date
 * @property string|null $action_note
 * @property int $customer_status
 * @property int|null $customer_payment
 * @property int|null $payment_rule
 * @property int|null $payment_condition
 * @property string|null $comment
 * @property float|null $price
 * @property float|null $price_fact
 * @property float|null $kpi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Crm\Act[] $act
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Crm\LidAction[] $actions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Crm\Act[] $acts
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Crm\Implement[] $implements
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Crm\MasterVolume[] $master
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Crm\SanitaryVolume[] $sanitary
 * @property-read \App\Models\Crm\LidStatus $status
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Crm\LidStatus[] $statuses
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Crm\Volume[] $volumes
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Lid newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Lid newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Lid query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Lid whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Lid whereActionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Lid whereActionNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Lid whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Lid whereContract($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Lid whereContractFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Lid whereContractSigned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Lid whereContractTransferred($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Lid whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Lid whereCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Lid whereCustomerPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Lid whereCustomerStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Lid whereDateStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Lid whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Lid whereKpi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Lid whereManager($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Lid whereManagerStarter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Lid wherePaymentCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Lid wherePaymentRule($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Lid wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Lid wherePriceFact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Lid whereServicing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Lid whereSite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Lid whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $date_from
 * @property string|null $date_to
 * @property-read \App\Models\Crm\Customer $customerData
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Crm\LidPayment[] $payments
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Lid whereDateFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Lid whereDateTo($value)
 */
class Lid extends Model
{
    protected $table = 'lids';
    protected $guarded = [];
/*    public $searchable = ['manager','site','manager_starter','customer','contract','date_from','date_to','contract_transferred','contract_signed','contract_file','servicing','action','date_start','action_date','action_note','customer_status','customer_payment','payment_rule','payment_condition','comment','price','price_fact','kpi'];*/


    public function statuses()
    {
        return $this->hasMany(LidStatus::class,'lid_id')->orderBy('created_at');
    }
    public function actions(){
        return $this->hasMany(LidAction::class,'lid_id')->orderBy('action_date');
    }
    public function status()
    {
        return $this->hasOne(LidStatus::class,'lid_id');
    }


    public function customer()
    {
        return $this->hasOne(Customer::class,'lid_id');
    }

    public function customerCount(){
        return $this->customer()
            ->selectRaw('status, count(*) as aggregate')
            ->groupBy('status');
    }

    public function client()
    {
        return $this->hasOne(Customer::class,'id','customer');
    }


    public function act(){
        return $this->hasMany(Act::class,'parent');
    }
    public function acts(){
        return $this->hasMany(Act::class,'parent')->orderBy('id');
    }

    public function master()
    {
        return $this->hasMany(MasterVolume::class);
    }

    public function sanitary()
    {
        return $this->hasMany(SanitaryVolume::class);
    }

    public function volumes(){
        return $this->hasMany(Volume::class,'lid_id');
    }
    public function implements(){
        return $this->hasMany(Implement::class,'lid_id');
    }

    public function volume(){
        $acts = $this->act();
        $res = [];
        foreach ($acts->getParent()->getRelationValue('act') as $act){
            $res[$act->id] = Volume::where('parent',$act->id);
        }

        return $res;
    }
    public function implement(){

        $acts = $this->act();
        $res = [];
        foreach ($acts->getParent()->getRelationValue('act') as $act){
            $res[$act->id] = Implement::where('parent',$act->id);
        }

        return $res;
    }

    public function payments(){
        return $this->hasMany(LidPayment::class,'lid_id');
    }

    public function finance(){
        return $this->hasMany(Finance::class,'lid_id');
    }






/*    public function volume(){

        return call_user_func([ new Act(),'volumes']);
    }*/


//    Public function getNameAttribute($name)
//    {
//        Return array_values(json_decode($name, true) ?: []);
//    }
//
//    Public function setNameAttribute($name)
//    {
//        $this->attributes['name'] = json_encode(array_values($name));
//    }

}

