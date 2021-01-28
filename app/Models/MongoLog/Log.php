<?php


namespace App\Models\MongoLog;

/*use Jenssegers\Mongodb\Eloquent\Model as Eloquent;


/**
 * App\Models\MongoLog\Log
 *
 * @property int|null $id
 * @property string|null $model
 * @property string|null $state
 * @property int|null $user
 * @property string|null $object
 * @property string|null $date
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property string|null $field
 * @property string|null $value
 * @property int|null $lid_id
 * @property int|null $act_id
 * @property int|null $implement_id
 * @property int|null $volume_id
 * @property int $_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MongoLog\Log newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MongoLog\Log newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MongoLog\Log query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MongoLog\Log whereActId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MongoLog\Log whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MongoLog\Log whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MongoLog\Log whereField($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MongoLog\Log whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MongoLog\Log whereImplementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MongoLog\Log whereLidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MongoLog\Log whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MongoLog\Log whereObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MongoLog\Log whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MongoLog\Log whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MongoLog\Log whereUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MongoLog\Log whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MongoLog\Log whereVolumeId($value)
 * @mixin \Eloquent
 */
/*/**
 * App\Models\MongoLog\Log
 *
 * @property int|null $id
 * @property string|null $model
 * @property string|null $state
 * @property int|null $user
 * @property string|null $object
 * @property string|null $date
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property string|null $field
 * @property string|null $value
 * @property int|null $lid_id
 * @property int|null $act_id
 * @property int|null $implement_id
 * @property int|null $volume_id
 * @property int $_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MongoLog\Log newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MongoLog\Log newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MongoLog\Log query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MongoLog\Log whereActId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MongoLog\Log whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MongoLog\Log whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MongoLog\Log whereField($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MongoLog\Log whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MongoLog\Log whereImplementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MongoLog\Log whereLidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MongoLog\Log whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MongoLog\Log whereObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MongoLog\Log whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MongoLog\Log whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MongoLog\Log whereUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MongoLog\Log whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MongoLog\Log whereVolumeId($value)
 * @mixin \Eloquent
 */
class Log extends Eloquent
{
    protected $connection = 'mongoLog';
    protected $collection = 'log';
    protected $fillable = ['id','model','state','user','field','value','object','lid_id','act_id','implement_id','volume_id','date'];

}*/



use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
//    protected $table = 'log';
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
