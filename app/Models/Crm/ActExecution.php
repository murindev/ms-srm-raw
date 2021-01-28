<?php


namespace App\Models\Crm;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Crm\ActExecution
 *
 * @property int $id
 * @property int $act_id
 * @property string|null $departured
 * @property string|null $arrived
 * @property int $audit
 * @property int $contract
 * @property int $finished
 * @property int $step
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActExecution newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActExecution newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActExecution query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActExecution whereActId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActExecution whereArrived($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActExecution whereAudit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActExecution whereContract($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActExecution whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActExecution whereDepartured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActExecution whereFinished($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActExecution whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActExecution whereStep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActExecution whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ActExecution extends Model
{
    protected $table = 'act_executions';
    protected $guarded = [];
}
