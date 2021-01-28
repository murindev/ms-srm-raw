<?php


namespace App\Http\Controllers\Chat;


use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Messenger\Message;
use App\Models\Messenger\MessageAddressee;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChatController extends Controller
{


    public $id;
    public $from;
    public $to;
    private $message;

    /**
     * @return mixed
     */
    public function async(){
        $request = request()->all();
        $func = $request['func'];
        isset($request['id']) ? $this->id = $request['id'] : $this->id = '';
        isset($request['message']) ? $this->message = $request['message'] : $this->message = '';
        isset($request['from']) ? $this->from = $request['from'] : $this->from = '';
        isset($request['to']) ? $this->to = $request['to'] : $this->to = '';

        return call_user_func_array([__CLASS__,$func],[]);
    }


    public function fetch()
    {
        return Message::with('addressee')
            ->where('from_user', $this->from)
            ->orWhereJsonContains('to_users',$this->from)
//            ->orWhereRaw('JSON_CONTAINS(to_users, '.(int)$this->from .')')
            ->whereBetween('created_at', [Carbon::now()->subWeek(), Carbon::now()])
            ->get()
            ->groupBy('group_name');
    }

    public function messageSeen(){
        return MessageAddressee::whereIn('id',$this->id)->update(['seen' => 0]);
    }

    public function sendMessage(Request $request)
    {
        $toUsersGroup = $request->to_users;
        $toUsers = $request->to_users;
        $key = array_search($request->user,$toUsersGroup);
        unset($toUsers[$key]);
        array_unique($toUsersGroup);
        natsort($toUsersGroup);


        $message = Message::create([
            'user_id' => $request->user,
            'message' => $request->message,
            'from_user' => $request->from_user,
            'to_users' => $toUsers,
            'group' => count($request->to_users) > 2 ?  1 :  0,
            'group_name' => implode('::',$toUsersGroup)
//            'group_name' => count($request->to_users) > 1 ? implode('::',$toUsersGroup) : implode(',',$request->to_users)
        ]);
        foreach ($toUsersGroup as $addressee) {
            MessageAddressee::create([
                'message_id' => $message->id,
                'user' => $addressee,
                'seen' => $addressee == $request->from_user ? 0 : 1,
            ]);

        }

        broadcast(new MessageSent(
            $request->user,
            $request->message,
            $request->from_user,
            $toUsersGroup,
            Message::with('addressee')->where('id',$message->id)->first()

        ));

        return  ['mess' => 'sent'];
    }
}

