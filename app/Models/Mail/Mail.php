<?php


namespace App\Models\Mail;


use Illuminate\Database\Eloquent\Model;

class Mail extends Model
{
    protected $table = '';
    protected $fillable = ['site','to','html','data'];

}
