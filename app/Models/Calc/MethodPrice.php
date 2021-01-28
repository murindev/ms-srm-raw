<?php

namespace App\Models\Calc;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Calc\MethodPrice
 *
 * @property int $id
 * @property int|null $entity
 * @property int $pest
 * @property int $method
 * @property int|null $remedy
 * @property float|null $expenses
 * @property float|null $cost
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Calc\MethodPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Calc\MethodPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Calc\MethodPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Calc\MethodPrice whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Calc\MethodPrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Calc\MethodPrice whereEntity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Calc\MethodPrice whereExpenses($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Calc\MethodPrice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Calc\MethodPrice whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Calc\MethodPrice wherePest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Calc\MethodPrice whereRemedy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Calc\MethodPrice whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MethodPrice extends Model
{
    protected $table = 'calc_method_prices';
    protected $guarded = [];
}
