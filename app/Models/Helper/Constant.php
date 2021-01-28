<?php


namespace App\Models\Helper;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Helper\Constant
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $value
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Constant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Constant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Constant query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Constant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Constant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Constant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Constant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Constant whereValue($value)
 * @mixin \Eloquent
 */
class Constant extends Model
{
    protected $table = 'helper_constants';

}
