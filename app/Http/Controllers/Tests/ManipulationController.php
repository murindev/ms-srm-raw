<?php


namespace App\Http\Controllers\Tests;


use App\Http\Controllers\Controller;

class ManipulationController extends Controller
{


    public function stats(){
        function a(){
            $csv = $_SERVER['DOCUMENT_ROOT'].'/csv/10723.csv';
            $content = file_get_contents($csv);
            $pass1 = explode(PHP_EOL,$content);
            $arr = [];
            foreach ($pass1 as $k => $e) if($k < 100){
                $e = str_replace('"','',$e);
                $arr[] = explode(',',$e);

            }
            return json_encode($arr,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        }
        function b(){
            $csv = $_SERVER['DOCUMENT_ROOT'].'/csv/uiui.csv';
            $content = file_get_contents($csv);
            $pass1 = explode(PHP_EOL,$content);
            $arr = [];
            foreach ($pass1 as $k => $e) if($e && $k < 100){
                $e = str_replace('"','',$e);
                $arr[] = explode('||',$e);
            }
            return json_encode($arr,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        }

//        $aa = a();:aa='".$aa."' :bb='".$bb."'
//        $bb = b();





        return <<<HTML
<div id='manipulations'><manipulation  ></manipulation></div>
<script src='/js/new/_manipulation.js'></script>

HTML;

    }

}
