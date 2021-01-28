<?php

namespace App\Models\Helper;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Helper\Tools
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Tools newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Tools newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Tools query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Tools whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Tools whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Tools whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Tools whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Tools whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Tools extends Model
{
    protected $table = 'helper_tools';
}
