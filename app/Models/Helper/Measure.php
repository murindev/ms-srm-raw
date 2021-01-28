<?php

namespace App\Models\Helper;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Helper\Measure
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Measure newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Measure newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Measure query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Measure whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Measure whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Measure whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Measure whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Measure extends Model
{
    protected $table = 'helper_measures';
}
