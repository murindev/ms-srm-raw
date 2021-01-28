<?php

namespace App\Models\Helper;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Helper\Penalty
 *
 * @property int $id
 * @property int|null $personnel
 * @property string|null $name
 * @property string|null $options
 * @property int|null $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Penalty newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Penalty newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Penalty query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Penalty whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Penalty whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Penalty whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Penalty whereOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Penalty wherePersonnel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Penalty whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Penalty whereValue($value)
 * @mixin \Eloquent
 */
class Penalty extends Model
{
    protected $table = 'helper_penalties';
}
