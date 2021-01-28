<?php

namespace App\Models\Crm;

use App\Helpers\Traits\HasManyKeyBy;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Crm\Act
 *
 * @property int $id
 * @property int|null $parent
 * @property string|null $act_nr
 * @property int $floating
 * @property string|null $floating_date_from
 * @property string|null $floating_date_to
 * @property string|null $prefer_time_from
 * @property string|null $prefer_time_to
 * @property int $finished
 * @property int $booking_act_signed
 * @property int $booking_act_transferred
 * @property string|null $booking_act_file
 * @property int $implement_act_signed
 * @property string|null $implement_act_file
 * @property string|null $address
 * @property string|null $lat
 * @property string|null $lon
 * @property string|null $destination
 * @property string|null $region
 * @property float|null $cost_remedy
 * @property float|null $cost_transport
 * @property float|null $cost_labor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Crm\Lid|null $acts
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Crm\Implement[] $implement
 * @property-read \App\Models\Crm\Lid|null $lid
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Crm\Volume[] $volume
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act whereActNr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act whereBookingActFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act whereBookingActSigned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act whereBookingActTransferred($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act whereCostLabor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act whereCostRemedy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act whereCostTransport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act whereDestination($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act whereFinished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act whereFloating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act whereFloatingDateFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act whereFloatingDateTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act whereImplementActFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act whereImplementActSigned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act whereLon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act whereParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act wherePreferTimeFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act wherePreferTimeTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $contact
 * @property string|null $phone
 * @property string|null $phone_ext
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act whereContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act wherePhoneExt($value)
 * @property int|null $creator
 * @property int|null $subact
 * @property string|null $phone_sub
 * @property string|null $phone_ext_sub
 * @property string|null $contact_name
 * @property string|null $comment
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act whereContactName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act whereCreator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act wherePhoneExtSub($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act wherePhoneSub($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Act whereSubact($value)
 */
class Act extends Model
{
    use HasManyKeyBy;
    protected $guarded = [];
    protected $fillable = [
        'parent','act_nr','statistic_id','creator','subact','contact','phone','phone_sub','phone_ext_sub','phone_ext','floating','floating_date_from','floating_date_to','prefer_time_from','prefer_time_to','finished','booking_act_signed','booking_act_transferred','booking_act_file','implement_act_signed','implement_act_file','address','lat','lon','destination','region','cash','cashless','property_type','contact_name','comment','cost_remedy','cost_transport','cost_labor','card_payment', 'overpay', 'contractor_fee', 'pay_back','created_at','updated_at','cash_tag','cashless_tag'];


    public function acts(){
        return $this->belongsTo(Lid::class,'parent');
    }
    public function lid(){
        return $this->belongsTo(Lid::class,'parent');
    }

    public function volume()
    {
        return $this->hasMany(Volume::class,'parent');
    }
    public function implement()
    {
        return $this->hasMany(Implement::class,'parent');
    }
    public function implementFirst()
    {
        return $this->hasOne(Implement::class,'parent');
    }
    public function volumes()
    {
        return $this->hasManyKeyBy('id',Volume::class,'parent');
    }
    public function volumesMobile()
    {
        return $this->hasMany(VolumeMobile::class,'parent');
    }
    public function implements()
    {
        return $this->hasManyKeyBy('id',Implement::class,'parent');
    }

    public function execution(){
        return $this->hasOne(ActExecution::class,'act_id');
    }
    public function auditPhotos(){
        return $this->hasMany(ActAuditPhoto::class,'act_id');
    }
}
