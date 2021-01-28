<?php

namespace App\Admin\Extensions\Nav;

use Encore\Admin\Facades\Admin;

class Links
{


    public function __toString()
    {
        $manager = Admin::user()->id;

        return <<<HTML
<!--<li>

    <a href="#">
      <i class="fa fa-envelope-o"></i>
      <span class="label label-success">4</span>
    </a>

</li>-->

<!--<li id="messenger"><Messenger :user='$manager'></li>-->

<!--<li>-->
<!--    <task-tracker  :user='$manager'></task-tracker>-->
<!--</li>-->
<!--<li id="pusher"><pusher></pusher></li>-->
<!--<script src="/js/new/messenger.js"></script>-->

HTML;
    }
}
