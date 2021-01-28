<?php


namespace App\Models\Crm;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Crm\ActAuditPhoto
 *
 * @property int $id
 * @property int $act_id
 * @property string $photo
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActAuditPhoto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActAuditPhoto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActAuditPhoto query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActAuditPhoto whereActId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActAuditPhoto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActAuditPhoto wherePhoto($value)
 * @mixin \Eloquent
 */
class ActAuditPhoto extends Model
{
    protected $table = 'act_audit_photos';
    protected  $guarded = [];
    protected $fillable = ['act_id','photo'];

}
