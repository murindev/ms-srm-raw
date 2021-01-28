<?php

namespace App\Models\Helper;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Helper\PaymentCondition
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\PaymentCondition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\PaymentCondition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\PaymentCondition query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\PaymentCondition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\PaymentCondition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\PaymentCondition whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\PaymentCondition whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PaymentCondition extends Model
{
    protected $table = 'helper_payment_conditions';
}
