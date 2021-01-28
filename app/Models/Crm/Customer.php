<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Crm\Customer
 *
 * @property int $id
 * @property int $lid_id
 * @property string|null $name
 * @property string|null $icon
 * @property int $status
 * @property string|null $organization
 * @property string|null $phone
 * @property string|null $phone_ext
 * @property string|null $address
 * @property string|null $addresses
 * @property string|null $email
 * @property string|null $emails
 * @property string|null $destination
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $city
 * @property string|null $region
 * @property string|null $lat
 * @property string|null $lon
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer whereAddresses($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer whereDestination($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer whereEmails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer whereLidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer whereLon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer whereOrganization($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer wherePhoneExt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $organization_long
 * @property string|null $address_source
 * @property string|null $inn
 * @property string|null $kpp
 * @property string|null $ogrn
 * @property int|null $zip
 * @property string|null $management_name
 * @property string|null $management_post
 * @property string|null $bank_payment
 * @property string|null $bank_ca
 * @property string|null $bank_bic
 * @property string|null $bank_account
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer whereAddressSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer whereBankAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer whereBankBic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer whereBankCa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer whereBankPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer whereInn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer whereKpp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer whereManagementName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer whereManagementPost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer whereOgrn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer whereOrganizationLong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer whereZip($value)
 * @property int|null $phone_sub
 * @property int|null $phone_ext_sub
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer wherePhoneExtSub($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Customer wherePhoneSub($value)
 */
class Customer extends Model
{
    protected $guarded = [];
    protected $fillable = ['lid_id','name','icon','status','organization','organization_long','phone','phone_sub','phone_ext','phone_ext_sub','address','addresses','address_source','email','emails','destination','city','region','lat','lon','inn','kpp','ogrn','zip','management_name','management_post','bank_payment','bank_ca','bank_bic','bank_account'];
    public $searchable = ['name', 'organization', 'organization_long', 'phone', 'phone_sub', 'phone_ext', 'phone_ext_sub', 'address', 'addresses', 'address_source', 'email', 'emails', 'destination', 'city', 'region', 'inn', 'kpp', 'ogrn', 'zip', 'management_name', 'management_post', 'bank_payment', 'bank_ca', 'bank_bic', 'bank_account'];

    public function setPhoneAttribute($value) {
        $this->attributes['phone'] = preg_replace("/[^0-9]/", '', $value);
    }
    public function setPhoneExtAttribute($value) {
        $this->attributes['phone_ext'] = preg_replace("/[^0-9]/", '', $value);
    }














}
