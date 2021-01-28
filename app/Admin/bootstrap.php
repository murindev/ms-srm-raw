<?php

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */


use Encore\Admin\Grid;
use Encore\Admin\Form;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid\Column;
use App\Admin\Extensions\Nav\Links;
use App\Admin\Extensions\Nav\Heading;
use App\Admin\Extensions\Form\LightBox;
use App\Admin\Extensions\Form\Field\DateButton;
use App\Admin\Extensions\Form\History;
use \App\Admin\Extensions\Form\Field\HasManyFlat;
use App\Admin\Extensions\Form\Field\TableFlat;
use App\Admin\Extensions\Form\Field\HiddenFlat;
use App\Admin\Extensions\Form\Field\DadataAddress;
use App\Admin\Extensions\Form\Field\DadataLatLon;
use App\Admin\Extensions\Form\Field\SelectFlat;
use App\Admin\Extensions\Form\Field\HtmlField;
use App\Admin\Extensions\Form\Field\ActCopy;

Encore\Admin\Form::forget(['map', 'editor']);

Form::extend('dateButton', DateButton::class);
Form::extend('lightBox', LightBox::class);
Form::extend('history', History::class);
Form::extend('hasManyFlat', HasManyFlat::class);
Form::extend('tableFlat', TableFlat::class);
Form::extend('hiddenFlat', HiddenFlat::class);
Form::extend('dadataAddress', DadataAddress::class);
Form::extend('dadataLatLon', DadataLatLon::class);
Form::extend('selectFlat', SelectFlat::class);
Form::extend('htmlField', HtmlField::class);
Form::extend('copyAct', ActCopy::class);
Admin::css([
    '/less/heiUI.css',
    '/less/helper.css',

]);

//Cookie::make('rtr', '7777777');

$vueRoute = [];
$route = request()->getPathInfo();
//$vueRoute[] = '/js/pusher.js';
//$vueRoute[] = '/js/messenger.js';
switch (true):
    case $route == "/":
        $vueRoute[] = '/js/main.page.js';
        break;
    case $route == "/mail/commercials":
        $vueRoute[] = '/js/page.commercial.letters.js';
        break;
    case $route == "/crm/statistic_main":
        $vueRoute[] = '/ts/crm.statistic.js';
        break;
    case $route == "/crm/statistic":
//        $vueRoute[] = '/js/statistic.lid.js'; // old
        $vueRoute[] = '/ts/crm.statistic.former.js';
        break;
    case $route == "/crm/statistic_test":
        $vueRoute[] = '/ts/crm.statistic.former.2.js';
        break;
    case $route == "/layout/lid":
        $vueRoute[] = '/ts/crm.lid.list.js';
        break;
    case $route == "/results/sales_funnel_personal":
        $vueRoute[] = '/ts/crm.funnel.personal.js';
        break;
    default:
        $vueRoute[] = '/ts/app.js';
        break;


endswitch;


Admin::navbar(function (\Encore\Admin\Widgets\Navbar $navbar) {
    $navbar->right(new Links());
    $navbar->left(new Heading());
});

Admin::js($vueRoute);





