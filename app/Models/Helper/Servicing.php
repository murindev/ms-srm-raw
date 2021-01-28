<?php

namespace App\Models\Helper;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Helper\Servicing
 *
 * @property int $id
 * @property int $sort
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Servicing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Servicing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Servicing query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Servicing whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Servicing whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Servicing whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Servicing whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Servicing whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Servicing extends Model
{
    protected $table = 'helper_servicing';
}
