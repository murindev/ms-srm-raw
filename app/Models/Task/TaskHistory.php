<?php


namespace App\Models\Task;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Task\TaskHistory
 *
 * @property int $id
 * @property int|null $task_id
 * @property string|null $subject
 * @property string|null $action
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskHistory whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskHistory whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskHistory whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskHistory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TaskHistory extends Model
{
    protected $table = 'task_history';
    protected $fillable = [
        'task_id',
        'subject',
        'subject_id',
        'action',
    ];

    public function task()
    {
        $this->belongsTo(Task::class, 'task_id');
    }

}
