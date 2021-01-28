<?php

namespace App\Models\Helper;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Helper\PaymentRule
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\PaymentRule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\PaymentRule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\PaymentRule query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\PaymentRule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\PaymentRule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\PaymentRule whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\PaymentRule whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PaymentRule extends Model
{
    protected $table = 'helper_payment_rules';
}
