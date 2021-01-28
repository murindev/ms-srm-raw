<?php


namespace App\Models\Mongo;


//use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Mongo\Lid
 *
 * @property int $id
 * @property string|null $manager
 * @property int|null $site
 * @property int|null $manager_starter
 * @property int|null $customer
 * @property string|null $contract
 * @property string|null $date_from
 * @property string|null $date_to
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Lid newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Lid newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Lid query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Lid whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Lid whereActionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Lid whereActionNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Lid whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Lid whereContract($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Lid whereContractFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Lid whereContractSigned($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Lid whereContractTransferred($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Lid whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Lid whereCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Lid whereCustomerPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Lid whereCustomerStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Lid whereDateFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Lid whereDateStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Lid whereDateTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Lid whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Lid whereKpi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Lid whereManager($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Lid whereManagerStarter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Lid wherePaymentCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Lid wherePaymentRule($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Lid wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Lid wherePriceFact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Lid whereServicing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Lid whereSite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Lid whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $cash_percent
 * @property int $cashless_percent
 * @property int $payment_system
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Lid whereCashPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Lid whereCashlessPercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Lid wherePaymentSystem($value)
 */
class Lid extends Model
{
//    protected $connection = 'mongodb';
//    protected $collection = 'lid';
    protected $guarded = [];

    public function acts()
    {
        return $this->embedsMany('acts');
    }

}
