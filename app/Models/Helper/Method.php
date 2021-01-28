<?php

namespace App\Models\Helper;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Helper\Method
 *
 * @property int $id
 * @property int $sort
 * @property string|null $name
 * @property int|null $cost
 * @property int|null $unit
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Method newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Method newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Method query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Method whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Method whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Method whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Method whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Method whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Method whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Method whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Method extends Model
{
    protected $table = 'helper_methods';
}
