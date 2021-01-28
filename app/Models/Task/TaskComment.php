<?php


namespace App\Models\Task;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Task\TaskComment
 *
 * @property int $id
 * @property int|null $task_id
 * @property int|null $parent
 * @property int|null $user
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskComment query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskComment whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskComment whereParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskComment whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskComment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskComment whereUser($value)
 * @mixin \Eloquent
 */
class TaskComment extends Model
{
    protected $table = 'task_comments';
    protected $fillable = [
        'task_id',
        'parent',
        'user',
        'comment',
    ];

    public function task()
    {
        $this->belongsTo(Task::class, 'task_id');
    }

}
