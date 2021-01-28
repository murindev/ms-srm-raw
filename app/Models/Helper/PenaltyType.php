<?php

namespace App\Models\Helper;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Helper\PenaltyType
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\PenaltyType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\PenaltyType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\PenaltyType query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\PenaltyType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\PenaltyType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\PenaltyType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\PenaltyType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PenaltyType extends Model
{
    protected $table = 'helper_penalty_types';
}
