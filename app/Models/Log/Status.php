<?php

namespace App\Models\Log;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Log\Status
 *
 * @property int $id
 * @property int|null $lid
 * @property int|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\Status newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\Status newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\Status query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\Status whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\Status whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\Status whereLid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\Status whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Log\Status whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Status extends Model
{
    protected $table = 'log_statuses';
}
