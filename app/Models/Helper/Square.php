<?php

namespace App\Models\Helper;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Helper\Square
 *
 * @property int $id
 * @property string $name
 * @property int $sort
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Square newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Square newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Square query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Square whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Square whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Square whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Square whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Square whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $name_short
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Square whereNameShort($value)
 */
class Square extends Model
{
    protected $table = 'helper_square';
}
