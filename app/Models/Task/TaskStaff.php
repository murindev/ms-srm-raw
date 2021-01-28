<?php


namespace App\Models\Task;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Task\TaskStaff
 *
 * @property int $id
 * @property int $task_id
 * @property int $user_id
 * @property string $role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskStaff newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskStaff newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskStaff query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskStaff whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskStaff whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskStaff whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskStaff whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskStaff whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskStaff whereUserId($value)
 * @mixin \Eloquent
 */
class TaskStaff extends Model
{

    protected $table = 'task_staffs';

    protected $fillable = [
        'task_id',
        'user_id',
        'role',
    ];

    public function task()
    {
        $this->belongsTo(Task::class, 'task_id');
    }

}
