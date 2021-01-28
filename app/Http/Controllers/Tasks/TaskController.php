<?php


namespace App\Http\Controllers\Tasks;


use App\Events\TaskEvent;
use App\Http\Controllers\Controller;
use App\Models\Task\Task;
use App\Models\Task\TaskComment;
use App\Models\Task\TaskHistory;
use App\Models\Task\TaskStaff;
use Carbon\Carbon;

class TaskController extends Controller
{
    public $id;
    public $values;
    private $value;
    public $field;




    /**
     * @return mixed
     */
    public function async(){
        $request = request()->all();
        $func = $request['func'];
        isset($request['id']) ? $this->id = $request['id'] : $this->id = '';
        isset($request['value']) ? $this->value = $request['value'] : $this->value = '';
        isset($request['values']) ? $this->values = $request['values'] : $this->values = '';
        isset($request['field']) ? $this->field = $request['field'] : $this->field = '';

        return call_user_func_array([__CLASS__,$func],[$this->values]);
    }

    public function getTask($id){
        return Task::with('files','checks','comments','history','staff')
            ->whereId($id)->first();
    }

    public function create(){
        $taskModel = Task::create($this->values['task']);
        if(count($this->values['files'])){
            $taskModel->files()->createMany($this->values['files']);
        }
        if(count($this->values['checks'])){
            $taskModel->checks()->createMany($this->values['checks']);
        }
        if($this->values['history']){
            $taskModel->history()->create($this->values['history']);
        }
        if(count($this->values['staff'])){
            $taskModel->staff()->createMany($this->values['staff']);
        }
        return $this->read();
    }


    public function read()
    {
        $q = Task::with('files', 'checks', 'comments', 'history', 'staff');
        // status
        $q->whereIn('status', $this->values['status']);
        // Start date
        if (array_key_exists('period', $this->values)) {
            if ($this->values['period']['start'] && $this->values['period']['end']) {
                $q->whereBetween('start_time', [
                    Carbon::make($this->values['period']['start']),
                    Carbon::make($this->values['period']['end'])
                ]);
            } elseif ($this->values['period']['start'] && is_null($this->values['period']['end'])) {
                $q->where('start_time', '>=', Carbon::make($this->values['period']['start']));
            } elseif (is_null($this->values['period']['start']) && $this->values['period']['end']) {
                $q->where('start_time', '<=', Carbon::make($this->values['period']['end']));
            }
        }
        $q->where(function ($queryPr) {
            $queryPr->whereHas('staff', function ($query) {
                $query->where('user_id', \Admin::user()->id);
            })->orWhere('director', \Admin::user()->id);
        });

        return $q->get();

    }



    public function update(){
        // files
        // checks
        // history
        // staff
        // comments
/*        if(array_key_exists('files',$this->values) && count($this->values['files'])){
            $this->updateStuff();
        }
        if(array_key_exists('checks',$this->values) && count($this->values['checks'])){
        }
        if(array_key_exists('history',$this->values) && $this->values['history']){
        }
        if(array_key_exists('staff',$this->values) && count($this->values['staff'])){
        }*/

    }

    public function delete(){

    }


// STAFF
    public function updateStuff(){

        $currentStaff = TaskStaff::where('task_id', $this->values['task_id'])
            ->where('role', $this->values['role'])
            ->pluck('user_id')->toArray();
// deleting
        $deleteStaff = array_diff($currentStaff, $this->values['model']);
        $del = [];
        $cre = [];
        if (count($deleteStaff)) {
            foreach ($deleteStaff as $delete_user_id) {
                $del[] = TaskStaff::where('user_id', $delete_user_id)
                    ->where('role', $this->values['role'])
                    ->where('task_id', $this->values['task_id'])
                    ->delete();
            }
        }
// creating
        $createStaff = array_diff($this->values['model'],$currentStaff);
        if(count($createStaff)){
            foreach ($createStaff as $create_user_id){
                $cre[] = TaskStaff::create([
                    'task_id' => $this->values['task_id'],
                    'user_id' => $create_user_id,
                    'role' => $this->values['role'],
                ]);
            }

        }
        return $this->read();
    }

    // COMMENTS

    public function recipients($taskID){
        $stuff = TaskStaff::where('task_id',$taskID)->pluck('user_id')->toArray();
        $director = Task::where('id',$taskID)->pluck('director')->toArray();
        $users = array_merge($stuff,$director);
        $key = array_search(\Admin::user()->id,$users);
        unset($users[$key]);
//        if(is_array($users)){
//            return $users;
//        } else {
//            return [$users];
//        }
        return $users;

    }

    public function commentCreate(){
        // task_id	parent	user	comment
        $comment = TaskComment::create($this->values);
        return $this->historyCreate([
            'task_id' => $this->values['task_id'],
            'subject' => 'comments',
            'subject_id' => $comment->id,
            'action' => $this->values['parent'] ? 'Ответ на комментарий #'.$comment->id :'Комментарий к задаче #'.$this->values['task_id'],
        ],$comment);



    }

    // HISTORY

    public function historyCreate($values,$replacement){
        $history = TaskHistory::create($values);

        TaskEvent::broadcast(
            \Admin::user()->id,
            [
                'task' => $this->getTask($this->values['task_id']),
                'history' => $history,
                'notice' => $replacement['comment'],
                'payload' => $replacement,
                'commit' => 'UPDATE_COMMENT',
                'to' => $this->recipients($this->values['task_id']),
            ]
        );
        /**
         * task_id
        subject
        action
         */

    }
}
