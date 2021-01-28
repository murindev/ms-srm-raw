<?php

namespace App\Models\Helper;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Helper\Equipment
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Equipment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Equipment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Equipment query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Equipment whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Equipment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Equipment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Equipment whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Equipment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Equipment extends Model
{
    protected $table = 'helper_equipments';
}
