<?php


namespace App\Models\Crm;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Crm\LidPayment
 *
 * @property int $id
 * @property int $lid_id
 * @property int|null $payment
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Crm\Lid $lid
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidPayment whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidPayment whereLidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidPayment wherePayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidPayment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LidPayment extends Model
{
    protected $table = 'lid_payments';
    protected $fillable = ['lid_id','payment','comment'];

    public function lid()
    {
        return $this->belongsTo(Lid::class,'lid_id');
    }

}





