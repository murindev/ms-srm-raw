<?php


namespace App\Models\Task;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Task\TaskFile
 *
 * @property int $id
 * @property int|null $task_id
 * @property string|null $link
 * @property string|null $ext
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskFile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskFile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskFile query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskFile whereExt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskFile whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskFile whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskFile whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TaskFile extends Model
{

    protected $table = 'task_files';
    protected $fillable = [
        'task_id',
        'link',
        'ext',
    ];

    public function task()
    {
        $this->belongsTo(Task::class, 'task_id');
    }
}
