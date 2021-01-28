<?php

namespace App\Models\Helper;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Helper\Status
 *
 * @property int $id
 * @property int $sort
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Status newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Status newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Status query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Status whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Status whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Status whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Status whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Status whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Status extends Model
{
    protected $table = 'helper_statuses';
}
