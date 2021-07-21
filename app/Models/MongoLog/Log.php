<?php


namespace App\Models\MongoLog;


use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = ['id','model','state','user','field','value','object','date','lid_id','act_id','implement_id','volume_id'];

    Public function getObjectAttribute($name)
    {
        Return array_values(json_decode($name, true) ?: []);
    }

    Public function setObjectAttribute($name)
    {
        $this->attributes['object'] = json_encode(array_values($name));
    }

    Public function getDateAttribute($name)
    {
        Return (int)$name;
    }
    Public function setDateAttribute($name)
    {
        $this->attributes['date'] = $name;
    }

}
