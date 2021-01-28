<?php


namespace App\Models\Crm;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Crm\ActAction
 *
 * @property int $id
 * @property int $lid_id
 * @property int|null $action
 * @property string|null $action_date
 * @property string|null $action_note
 * @property int|null $manager
 * @property int|null $salt
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActAction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActAction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActAction query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActAction whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActAction whereActionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActAction whereActionNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActAction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActAction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActAction whereLidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActAction whereManager($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActAction whereSalt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\ActAction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ActAction extends Model
{
    protected $table = 'act_actions';
    protected $fillable = [
        'lid_id',
        'action',
        'action_note',
        'manager',
        'salt',
        'action_date'
    ];

}
