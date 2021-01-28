<?php


namespace App\Models\Task;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Task\TaskCheck
 *
 * @property int $id
 * @property int|null $task_id
 * @property string|null $text
 * @property int|null $checked
 * @property int $proof
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskCheck newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskCheck newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskCheck query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskCheck whereChecked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskCheck whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskCheck whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskCheck whereProof($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskCheck whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskCheck whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Task\TaskCheck whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TaskCheck extends Model
{
    protected $table = 'task_checks';
    protected $fillable = [
        'task_id',
        'text',
        'checked',
    ];

    public function task()
    {
        $this->belongsTo(Task::class, 'task_id');
    }
}
