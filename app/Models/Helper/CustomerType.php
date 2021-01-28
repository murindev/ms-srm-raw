<?php

namespace App\Models\Helper;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Helper\CustomerType
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\CustomerType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\CustomerType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\CustomerType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\CustomerType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\CustomerType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\CustomerType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\CustomerType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\CustomerType whereValue($value)
 * @mixin \Eloquent
 */
class CustomerType extends Model
{
    protected $table = 'helper_customer_types';
}
