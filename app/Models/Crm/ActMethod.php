<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Crm\ActMethod
 *
 * @property int $id
 * @property int|null $act_id
 * @property int $method
 * @property int $lid_id
 * @property int $vol_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActMethod whereActId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActMethod whereLidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActMethod whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActMethod whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActMethod whereVolId($value)
 * @mixin \Eloquent
 */
class ActMethod extends Model
{
    protected $table = 'act_methods';
}
