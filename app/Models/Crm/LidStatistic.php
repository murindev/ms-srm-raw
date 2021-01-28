<?php


namespace App\Models\Crm;


use App\Models\Finance\Finance;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Crm\LidStatistic
 *
 * @property int $id
 * @property int|null $lid_id
 * @property string|null $date_start
 * @property string|null $cost_date
 * @property int|null $nr
 * @property string|null $date
 * @property string|null $time
 * @property int|null $site
 * @property int|null $manager
 * @property int|null $call_status
 * @property string|null $cause
 * @property string|null $address
 * @property string|null $cash
 * @property string|null $cashless
 * @property string|null $phone
 * @property string|null $name
 * @property string|null $contract
 * @property string|null $implement_date
 * @property string|null $customer
 * @property string|null $refusal
 * @property string|null $col
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatistic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatistic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatistic query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatistic whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatistic whereCallStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatistic whereCash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatistic whereCashless($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatistic whereCause($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatistic whereCol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatistic whereContract($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatistic whereCostDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatistic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatistic whereCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatistic whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatistic whereDateStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatistic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatistic whereImplementDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatistic whereLidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatistic whereManager($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatistic whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatistic whereNr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatistic wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatistic whereRefusal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatistic whereSite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatistic whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatistic whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LidStatistic extends Model
{

    protected $guarded = [];
    /*protected $fillable = [
        'lid_id',
        'customer_id',
        'act_id',
        'date_start',
        'cost_date',
        'nr',
        'date',
        'time',
        'site',
        'manager',
        'call_status',
        'app_status',
        'cause',
        'address',
        'cash',
        'cashless',
        'phone',
        'phone_sub',
        'phone_ext',
        'phone_ext_sub',
        'name',
        'contract',
        'offer_sent',
        'implement_date',
        'refusal',
        'col',
        'editing',
        'region',
        'lat',
        'lon',
        'property_type',
        'destination',
        'card_payment',
        'overpay',
        'contractor_fee',
        'pay_back',
        'cash_tag','cashless_tag',
        'created_at',
        'updated_at'
    ];*/

//    public function getLid(){
//        if ( ! $this->getAttribute('lid_id')) {
//            return null;
//        }
//        return $this->getAttribute('lids');
//    }

    public function setPhoneAttribute($value) {
        $this->attributes['phone'] = preg_replace("/[^0-9]/", '', $value);
    }
    public function setPhoneExtAttribute($value) {
        $this->attributes['phone_ext'] = preg_replace("/[^0-9]/", '', $value);
    }

    public function lid(){
        return $this->hasOne(Lid::class,'id','lid_id');
    }
    public function customer(){
        return $this->hasOne(Customer::class,'id','customer_id');
    }
    public function act(){
        return $this->hasOne(Act::class, 'id', 'act_id');
    }
    public function volume(){
        return $this->hasOne(Volume::class, 'statistic_id', 'id');
    }
    public function volumes(){
        return $this->hasMany(Volume::class, 'statistic_id', 'id');
    }
    public function volumessum(){
        return $this->hasMany(Volume::class, 'statistic_id', 'id');
    }
    public function finance(){
        return $this->hasMany(Finance::class, 'lid_id', 'lid_id');
    }

    // filters

    public function scopeStartsAfter($query, $date)
    {
        return $query->where('date', '<=', Carbon::parse($date));
    }

    public function scopePhonesRaw($query, $value)
    {
        $val = preg_replace("/[^,.0-9]/", '', $value);
        return $query->where('phone_sub', 'like', '%'.$val.'%')
            ->orWhere('phone_sub', 'like', '%'.$val.'%');
//            ->orWhere('customer.phone', 'like', '%'.$value.'%')
//            ->orWhere('customer.phone_ext', 'like', '%'.$value.'%')

    }
}
