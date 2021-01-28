<?php

namespace App\Http\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Crm\ActAuditPhoto;
use Encore\Admin\Form\Field;
//use Illuminate\Http\Request;

class ImageAuditPhotoController extends Controller
{

    public function uploadImageContent()
    {

        $this->validate(request(), [
            'file' => 'mimes:jpeg,pdf,jpg,gif,png'
        ]);

        $file = request()->file('file');
//        $filename = md5(uniqid()).'.'.$file->getClientOriginalExtension();

        $imagePath = "/storage/audit/photo/";

        $file->move(public_path() . $imagePath, $file->getClientOriginalName());

//        $tt = ActAuditPhoto::create(['' => ,'photo' => $file->getClientOriginalName()]);

/*        $url = $imagePath . $filename;






        $ff = [
            'uploaded' => 1,
            'fileName' => $filename,
            'url' => $url
        ];*/

//        echo json_encode($ff);

    }


    public function attach()
    {
        $this->uploadImageContent(request()->all());
    }

    public function attachTask()
    {
//        $this->validate(request(), [
//            'file' => 'mimes:jpeg,pdf,jpg,gif,png'
//        ]);

        $file = request()->file('file');
        $filename = md5(uniqid()).'.'.$file->getClientOriginalExtension();


        $imagePath = "/storage/task/";



        $file->move(public_path() . $imagePath, $filename);

        $url = $imagePath . $filename;

        $ff = [
            'uploaded' => 1,
            'fileName' => $filename,
            'url' => $url
        ];

        return $ff;

    }
}
