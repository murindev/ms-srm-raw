<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Crm\SanitaryVolume
 *
 * @property int $id
 * @property int $lid_id
 * @property int|null $master
 * @property string|null $start_date
 * @property string|null $end_date
 * @property int|null $increase
 * @property int|null $profit
 * @property int|null $contract
 * @property int|null $payment
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\SanitaryVolume newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\SanitaryVolume newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\SanitaryVolume query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\SanitaryVolume whereContract($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\SanitaryVolume whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\SanitaryVolume whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\SanitaryVolume whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\SanitaryVolume whereIncrease($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\SanitaryVolume whereLidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\SanitaryVolume whereMaster($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\SanitaryVolume whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\SanitaryVolume wherePayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\SanitaryVolume whereProfit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\SanitaryVolume whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\SanitaryVolume whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SanitaryVolume extends Model
{
    protected $table = 'sanitary_volumes';
}
