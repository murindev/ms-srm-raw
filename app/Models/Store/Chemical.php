<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Store\Chemical
 *
 * @property int $id
 * @property string|null $name
 * @property int $measure
 * @property int|null $expenses
 * @property float|null $expenses_meter
 * @property int $price_net
 * @property int|null $price_extra
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store\Chemical newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store\Chemical newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store\Chemical query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store\Chemical whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store\Chemical whereExpenses($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store\Chemical whereExpensesMeter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store\Chemical whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store\Chemical whereMeasure($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store\Chemical whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store\Chemical wherePriceExtra($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store\Chemical wherePriceNet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store\Chemical whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Chemical extends Model
{
    protected $table = 'store_chemicals';

//    public
}
