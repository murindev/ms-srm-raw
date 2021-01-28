<?php

namespace App\Admin\Controllers;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Message;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;

class ChatsController extends Controller
{
    public static function manager(){
        $user = config('const.admin')->user()->toArray();
        return $user['id'];
    }

    public function index(Content $content)
    {
        return $content
            ->header('Index')
            ->description('description')
            ->body("<meta name='csrf-token' content='".csrf_token()."'><div id='chat'><chat manager='".$this->manager()."' tokens='".csrf_token()."'></chat></div><script src='/js/new/chat.js'></script>");

    }


}
