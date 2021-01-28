<?php

namespace App\Models\Log;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Log\Implement
 *
 * @property int $id
 * @property string $date
 * @property int $salt
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\Implement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\Implement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\Implement query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\Implement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\Implement whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\Implement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\Implement whereSalt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\Implement whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Implement extends Model
{
    protected $table = 'log_implements';
}
