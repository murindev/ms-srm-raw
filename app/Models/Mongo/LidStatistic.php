<?php


namespace App\Models\Mongo;


//use Jenssegers\Mongodb\Eloquent\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Mongo\LidStatistic
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereCallStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereCash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereCashless($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereCause($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereCol($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereContract($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereCostDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereDateStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereImplementDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereLidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereManager($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereNr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereRefusal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereSite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $act_id
 * @property int|null $customer_id
 * @property int|null $app_status
 * @property int $cash_tag
 * @property int $cashless_tag
 * @property string|null $phone_ext
 * @property string|null $phone_sub
 * @property string|null $phone_ext_sub
 * @property int $customer_status
 * @property int $lid_customer_payment
 * @property int $lid_payment_rule
 * @property int $lid_payment_condition
 * @property int $lid_payment_system
 * @property int $lid_price
 * @property int $lid_price_fact
 * @property int $lid_cash_percent
 * @property int $lid_cashless_percent
 * @property int|null $offer_sent
 * @property int|null $editing
 * @property string|null $lat
 * @property string|null $lon
 * @property string $region
 * @property string|null $destination
 * @property int|null $property_type
 * @property int $card_payment
 * @property int $overpay
 * @property int $contractor_fee
 * @property int $pay_back
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereActId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereAppStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereCardPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereCashTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereCashlessTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereContractorFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereCustomerStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereDestination($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereEditing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereLidCashPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereLidCashlessPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereLidCustomerPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereLidPaymentCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereLidPaymentRule($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereLidPaymentSystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereLidPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereLidPriceFact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereLon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereOfferSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereOverpay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic wherePayBack($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic wherePhoneExt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic wherePhoneExtSub($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic wherePhoneSub($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic wherePropertyType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\LidStatistic whereRegion($value)
 */
class LidStatistic extends Model
{
//    protected $connection = 'mongodb';
//    protected $collection = 'lid_statistic';
    protected $guarded = [];

}
