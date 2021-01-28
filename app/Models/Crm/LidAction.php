<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Crm\LidAction
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
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidAction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidAction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidAction query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidAction whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidAction whereActionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidAction whereActionNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidAction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidAction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidAction whereLidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidAction whereManager($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidAction whereSalt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\LidAction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LidAction extends Model
{
    protected $table = 'lid_actions';
    protected $fillable = ['lid_id','action','date','comment','manager','salt'];
}





