<?php


namespace App\Http\Controllers\Mongo;


use App\Http\Controllers\Controller;
use App\Models\Mongo\Test;

class TestController extends Controller
{

    public function test(){
        Test::create([
            'title' => 'Hello888',
            'text' => 'world888',
        ]);

        $t = Test::all();

        dump(json_decode($t));

//        return json_decode($t);

    }

}
