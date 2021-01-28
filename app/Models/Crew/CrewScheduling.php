<?php

namespace App\Models\Crew;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Crew\Crew
 *
 * @property int $id
 * @property string|null $name
 * @property string $dewe
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crew\CrewScheduling newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crew\CrewScheduling newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crew\CrewScheduling query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crew\CrewScheduling whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crew\CrewScheduling whereDewe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crew\CrewScheduling whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crew\CrewScheduling whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crew\CrewScheduling whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $user_id
 * @property string|null $state
 * @property string|null $date
 * @property string|null $work_start
 * @property int|null $work_start_proof
 * @property string|null $work_finish
 * @property int|null $work_finish_proof
 * @property string|null $comment
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crew\CrewScheduling whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crew\CrewScheduling whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crew\CrewScheduling whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crew\CrewScheduling whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crew\CrewScheduling whereWorkFinish($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crew\CrewScheduling whereWorkFinishProof($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crew\CrewScheduling whereWorkStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crew\CrewScheduling whereWorkStartProof($value)
 */
class CrewScheduling extends Model
{
    protected $table = 'crew_scheduling';
    protected $fillable = [
        'user_id',
        'state',
        'date',
        'work_start',
        'work_start_proof',
        'work_finish',
        'work_finish_proof',
    ];
}
