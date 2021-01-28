<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Crm\LidStatus
 *
 * @property int $id
 * @property int $lid_id
 * @property string $status
 * @property int $manager
 * @property int $salt
 * @property string $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Crm\Lid $lid
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatus whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatus whereLidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatus whereManager($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatus whereSalt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatus whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LidStatus extends Model
{
    protected $table = 'lid_statuses';
    protected $fillable = ['lid_id','status','manager','salt','date'];

    public function lid()
    {
        return $this->belongsTo(Lid::class,'lid_id');
    }

    public static function statusShow($lidID)
    {
        return view('form.status_show',['data' => LidStatus::where('lid_id',$lidID)->get()]);

    }

    public function statusAdd($model)
    {
        $lidStatus = new LidStatus();

        $lidStatus->lid_id = $model->lid_id;
        $lidStatus->status = $model->status;
        $lidStatus->date = $model->date;
        $lidStatus->save();

    }
}
