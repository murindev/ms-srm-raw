<?php

namespace App\Models\Helper;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Helper\PestGroup
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\PestGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\PestGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\PestGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\PestGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\PestGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\PestGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\PestGroup whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PestGroup extends Model
{
    protected $table = 'helper_pest_groups';
}
