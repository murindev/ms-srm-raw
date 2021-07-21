<?php


namespace App\Http\Controllers\Mail;


use App\Http\Controllers\Controller;
use App\Models\Mail\Mail;

class MailController extends Controller
{
    public function curlInit($url,$data){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        $output = curl_exec($ch);

        if ($output === FALSE) {
            return curl_error($ch);
        } else {
            return $output;
        }
        curl_close($ch);
    }
    public function save($output,$data){
        return [
            'output' => $output,
            'data' => $data,
        ];
    }
    public function commercialSesteam(){
        $output = $this->curlInit('https://sesteam.ru/mail/letters',[
            'to' => request()->get('to'),
            'subject' => request()->get('subject'),
            'html' => request()->get('html')
        ]);
        return $output;
//        return $this->save($output,$output);
    }



}
