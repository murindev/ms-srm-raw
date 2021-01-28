<?php

namespace App\Models\Helper;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Helper\Action
 *
 * @property int $id
 * @property int $sort
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Action newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Action newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Action query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Action whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Action whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Action whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Action whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Action whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Action extends Model
{
    protected $table = 'helper_actions';
}
