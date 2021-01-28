<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Crm\MasterVolume
 *
 * @property int $id
 * @property int|null $parent
 * @property int $lid_id
 * @property int $master
 * @property string|null $start_date
 * @property string|null $end_date
 * @property int|null $increase
 * @property string|null $profit
 * @property int|null $contract
 * @property int|null $payment
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\MasterVolume newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\MasterVolume newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\MasterVolume query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\MasterVolume whereContract($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\MasterVolume whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\MasterVolume whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\MasterVolume whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\MasterVolume whereIncrease($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\MasterVolume whereLidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\MasterVolume whereMaster($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\MasterVolume whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\MasterVolume whereParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\MasterVolume wherePayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\MasterVolume whereProfit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\MasterVolume whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\MasterVolume whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MasterVolume extends Model
{
    protected $guarded = [];
    protected $table = 'master_volumes';
}
