<?php


namespace App\Admin\Extensions\Nav;


use Encore\Admin\Auth\Database\Menu;
use Encore\Admin\Facades\Admin;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Renderable;

class Secret
{
    public function __toString()
    {
        return <<< HTML
<input type="hidden" value="tttttttttttttttt">
HTML;

        // TODO: Implement __toString() method.
    }

}
