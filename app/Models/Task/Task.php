<?php


namespace App\Models\Task;

use App\Helpers\Traits\HasManyKeyBy;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Task\Task
 *
 * @property int $id
 * @property int|null $parent
 * @property string|null $name
 * @property string|null $description
 * @property string|null $priority
 * @property int|null $director
 * @property string|null $start_time
 * @property string|null $deadline
 * @property int|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Task\TaskCheck[] $checks
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Task\TaskComment[] $comments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Task\TaskFile[] $files
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Task\TaskHistory[] $history
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Task\TaskStaff[] $staff
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\Task newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\Task newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\Task query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\Task whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\Task whereDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\Task whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\Task whereDirector($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\Task whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\Task whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\Task whereParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\Task wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\Task whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\Task whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\Task whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Task extends Model
{

    use HasManyKeyBy;

    protected $fillable = [
        'parent',
        'name',
        'description',
        'priority',
        'director',
        'start_time',
        'deadline',
        'status',
    ];

    public function staff()
    {
        return $this->hasMany( TaskStaff::class, 'task_id');
    }

    public function files()
    {
        return $this->hasMany(TaskFile::class,'task_id');
    }

    public function checks()
    {
        return $this->hasMany(TaskCheck::class, 'task_id');
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class, 'task_id');
    }

    public function history()
    {
        return $this->hasMany( TaskHistory::class, 'task_id');
    }
}
