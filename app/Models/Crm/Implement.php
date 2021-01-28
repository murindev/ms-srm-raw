<?php

namespace App\Models\Crm;

use App\Models\Rules\Rule;
use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Crm\Implement
 *
 * @property int $id
 * @property int|null $parent
 * @property int|null $lid_id
 * @property int|null $master
 * @property string|null $start_date
 * @property string|null $end_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Crm\Act|null $act
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Implement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Implement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Implement query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Implement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Implement whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Implement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Implement whereLidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Implement whereMaster($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Implement whereParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Implement whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Implement whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $date
 * @property int $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Implement whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Implement whereUser($value)
 * @property string|null $note
 * @property int|null $reason
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Rules\Rule[] $rules
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Implement whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Implement whereReason($value)
 */
class Implement extends Model
{
    protected $guarded = [];

    public function act(){
        return $this->belongsTo(Act::class,'parent');
    }

    public static function masters()
    {
        return Administrator::whereHas('roles', function ($q) {
            $q->where('role_id', 3); // role_start_dateid
        })->get()->pluck('name', 'id');
    }

    public function rules(){
        return $this->hasMany(Rule::class,'implement_id');
    }

}
