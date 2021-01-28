<?php

namespace App\Models\Helper;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Helper\Rules
 *
 * @property int $id
 * @property int|null $role
 * @property int|null $control
 * @property string|null $name
 * @property string|null $name_short
 * @property int $strict
 * @property string|null $value
 * @property int|null $subject
 * @property int $start_end
 * @property int $sort
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Rules newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Rules newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Rules query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Rules whereControl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Rules whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Rules whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Rules whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Rules whereNameShort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Rules whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Rules whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Rules whereStartEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Rules whereStrict($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Rules whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Rules whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Rules whereValue($value)
 * @mixin \Eloquent
 * @property int $penalty
 * @property int $gap
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Rules whereGap($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Rules wherePenalty($value)
 */
class Rules extends Model
{
    protected $table = 'helper_rules';
}
