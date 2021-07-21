<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Artisan::call('view:clear');

use App\Models\Crm\Lid;

Route::prefix('vuex')->group(function () {
    Route::any('/customer', '\App\Http\Vuex\CustomerVuex@index');
    Route::any('/lid', '\App\Http\Vuex\LidVuex@index');
    Route::any('/act', '\App\Http\Vuex\ActVuex@index');
    Route::any('/crm-statistic', '\App\Http\Vuex\CrmStatisticVuex@index');
    Route::any('/volume', '\App\Http\Vuex\VolumeVuex@index');
    Route::any('/implement', '\App\Http\Vuex\ImplementVuex@index');
    Route::any('/finance', '\App\Http\Vuex\FinanceVuex@index');
    Route::any('/funnel', '\App\Http\Controllers\Statistics\StatisticCall@index');
    Route::any('/statistic-finance', '\App\Http\Controllers\Statistics\StatisticFinance@index');
});

Route::prefix('qb')->group(function () {
    Route::get('/statistic', '\App\Http\QB\StatisticController@index');
    Route::get('/lid', '\App\Http\QB\LidController@index');
});

Route::prefix('dart')->group(function () {
    Route::any('/pc', '\App\Http\Dart\MasterController@index');
});


Route::get('/mongo', '\App\Http\Controllers\Mongo\TestController@test');
Route::get('/testlid', '\App\Http\Controllers\Tests\TestLid@index');



Route::group(['middleware' => ['jwt.verify']], function () {
    Route::get('user', 'UserController@getAuthenticatedUser');
    Route::get('closed', 'DataController@closed');
});

Route::post('/verify', '\App\Http\Controllers\LoginJSController@getToken');

// letters
Route::any('/sendletters/sesteam', '\App\Http\Controllers\Mail\MailController@commercialSesteam');
// eof letters


// ASYNC

Route::any('/dadata/async', '\App\Http\Controllers\Dadata\Dadata@async');
Route::any('/helper/async', '\App\Http\Controllers\HelperController@async');
Route::any('/customer/async', '\App\Http\Controllers\Customer\CustomerController@async');
Route::any('/lid/async', '\App\Http\Controllers\Lid\LidController@async');
Route::any('/act/async', '\App\Http\Controllers\Act\ActController@async');
Route::any('/log/async', '\App\Http\Controllers\Log\LogController@async');
Route::any('/volume/async', '\App\Http\Controllers\Volume\VolumeController@async');
Route::any('/implement/async', 'Implement\ImplementController@async');
Route::any('/map/async', '\App\Http\Controllers\Logistic\MapController@async');
Route::any('/lid_payment/async', '\App\Http\Controllers\Payment\LidPaymentController@async');
Route::any('/rules/async', '\App\Http\Controllers\Rules\RulesController@async');
// TASK
Route::any('/task', '\App\Http\Controllers\Tasks\TaskController@async');


// crew
Route::any('/crew_scheduling/async', '\App\Http\Controllers\Crew\CrewSchedulingController@async');

Route::any('/mongo_log/async', '\App\Http\Controllers\MongoLog\LogController@async');

Route::any('/lid/mongo', '\App\Http\Controllers\Mongo\LidController@async');
// metrika
Route::any('/metrika/visit/async', '\App\Http\Controllers\Metrika\VisitController@async');
// expenses
Route::any('/expenses/async', '\App\Http\Controllers\Expenses\ExpenseController@async');


// statistics
Route::any('/lid_statistic/async', '\App\Http\Controllers\Lid\LidStatisticController@async');
Route::any('/statistic/crm/async', '\App\Http\Controllers\Lid\LidStatisticController@async');
//Route::any('/manipulations', '\App\Http\Controllers\Tests\ManipulationController@stats');
Route::any('/manipulations', '\App\Http\Controllers\Lid\LidStatisticController@async');


// EOF statistics

// settings
Route::any('/settings/async', '\App\Http\Controllers\SettingsController@async');
// EOF settings

// messemger

//Route::get('/messages', '\App\Http\Controllers\Chat\ChatController@fetchMessages');
Route::post('/messages', '\App\Http\Controllers\Chat\ChatController@sendMessage');
Route::post('/messenger/async', '\App\Http\Controllers\Chat\ChatController@async');

// schedule

Route::get('/schedule/master', '\App\Http\Controllers\Ajax\LogisticController@printMasterSchedule');

Route::get('/print/schedule', '\App\Http\Controllers\Printer\PrinterController@printSchedule');



// MOBILE


Route::any('/mob/test', '\App\Http\Controllers\Personal\MasterController@async'); // ->middleware('mobile')


// EOF MOBILE


Route::any('/attach', 'ImageController@attach');
Route::any('/attachtask', 'ImageController@attachTask');

//MAIN LOGISTIC MAP
Route::get('/display/logistic_map', '\App\Http\Controllers\Display\Info@logisticMap');


Route::post('/logistic/map/new', '\App\Http\Controllers\Ajax\LogisticController@new');

Route::post('/logistic/map/update', '\App\Http\Controllers\Ajax\LogisticController@update');
Route::post('/logistic/map/update_implement', '\App\Http\Controllers\Ajax\LogisticController@updateImplement');
Route::get('/api/getter/methods', '\App\Http\Controllers\Ajax\GetterController@methods');


// DADATA

Route::get('/dadata/curl', '\App\Http\Controllers\Ajax\Dadata@address');
Route::get('/dadata/inn', '\App\Http\Controllers\Ajax\Dadata@inn');
Route::get('/dadata/osm', '\App\Http\Controllers\Ajax\Dadata@osm');


// test section

Route::get('/logistic/map/new', '\App\Http\Controllers\Ajax\LogisticController@get');
Route::get('/logistic/map/get_by_geo', '\App\Http\Controllers\Ajax\LogisticController@getByGeo');
Route::post('/logistic/map/update', '\App\Http\Controllers\Ajax\LogisticController@update');
Route::get('/logistic/map/many', '\App\Http\Controllers\Ajax\LogisticController@getMany');
Route::get('/logistic/map/get_equipment', '\App\Http\Controllers\Ajax\LogisticController@getEquipment');
Route::post('/logistic/map/update_equipment', '\App\Http\Controllers\Ajax\LogisticController@updateEquipment');
Route::post('/logistic/map/acts_fulfilled', '\App\Http\Controllers\Ajax\LogisticController@actsFulfilled');
Route::get('/logistic/map/floating', '\App\Http\Controllers\Ajax\LogisticController@floating');
Route::get('/logistic/map/get_acts', '\App\Http\Controllers\Ajax\LogisticController@getActs');

Route::get('/logistic/map/update_implements', '\App\Http\Controllers\Ajax\LogisticController@updateImplements');

// copy act
Route::get('/copy_act', '\App\Http\Controllers\Ajax\ActController@copyAct');

// vue lid
Route::get('/ajax/lid/update_field', '\App\Admin\Controllers\Vue\LidController@updateField');
Route::get('/ajax/lid/log', '\App\Admin\Controllers\Vue\LidController@log');
Route::get('/ajax/lid/get_act_relations', '\App\Admin\Controllers\Vue\LidController@getActRelations');
Route::get('/ajax/lid/add_volume', '\App\Admin\Controllers\Vue\LidController@addVolume');
Route::get('/ajax/lid/delete_volume', '\App\Admin\Controllers\Vue\LidController@deleteVolume');
Route::get('/ajax/lid/add_implement', '\App\Admin\Controllers\Vue\LidController@addImplement');
Route::get('/ajax/lid/delete_implement', '\App\Admin\Controllers\Vue\LidController@deleteImplement');
Route::get('/ajax/lid/add_act_relation', '\App\Admin\Controllers\Vue\LidController@addActRelation');
Route::get('/ajax/lid/act_per_date', '\App\Admin\Controllers\Vue\LidController@actPerDate');
Route::get('/ajax/lid/save_float_acts', '\App\Admin\Controllers\Vue\LidController@saveFloatActs');
Route::get('/ajax/lid/get_acts', '\App\Admin\Controllers\Vue\LidController@getActs');
Route::get('/ajax/lid/delete_act', '\App\Admin\Controllers\Vue\LidController@deleteAct');
Route::get('/ajax/lid/customer_search', '\App\Admin\Controllers\Vue\LidController@customerSearch');
Route::get('/ajax/lid/add_empty_act', '\App\Admin\Controllers\Vue\LidController@addEmptyAct');
Route::get('/ajax/lid/get_volumes', '\App\Admin\Controllers\Vue\LidController@getVolumes');

// Methods prices
Route::get('/ajax/price/init_table', '\App\Admin\Controllers\Helper\PestController@initTable');
Route::get('/ajax/price/save_field', '\App\Admin\Controllers\Helper\PestController@saveField');
Route::get('/ajax/price/add_remedy', '\App\Admin\Controllers\Helper\PestController@addRemedy');
Route::get('/ajax/price/remove_remedy', '\App\Admin\Controllers\Helper\PestController@removeRemedy');

// rules

Route::any('/logistic/map/rules', '\App\Http\Controllers\Rules\RulesController@async');
Route::any('/async/act', '\App\Http\Controllers\Act\ActController@async');

Route::any('/mobile-master', '\App\Http\Mobile\MasterController@index');
//Route::any('/mobile-master-photo', '\App\Http\Mobile\ImageAuditPhotoController@uploadImageContent');

Route::any('/tratra', '\App\Http\Controllers\Statistics\StatisticCall@index');
Route::any('/ooo', '\App\Http\Controllers\Statistics\StatisticFinance@test');
