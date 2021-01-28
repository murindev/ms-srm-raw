<?php

namespace App\Models\Messenger;

use App\Admin\Controllers\AuthController;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Traits\HasManyKeyBy;
/**
 * App\Models\Messenger\Message
 *
 * @property int $id
 * @property int $user_id
 * @property string $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $from_user
 * @property string|null $to_users
 * @property int|null $group
 * @property string|null $group_name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Messenger\MessageAddressee[] $addressee
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messenger\Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messenger\Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messenger\Message query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messenger\Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messenger\Message whereFromUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messenger\Message whereGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messenger\Message whereGroupName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messenger\Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messenger\Message whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messenger\Message whereToUsers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messenger\Message whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messenger\Message whereUserId($value)
 * @mixin \Eloquent
 */
class Message extends Model
{
    use HasManyKeyBy;
    protected $fillable = ['message','user_id','from_user','to_users','group','group_name'];

/*    public function user()
    {
    	return $this->belongsTo(User::class);//AuthController
    }*/
    public function addressee(){
        return $this->hasManyKeyBy('user',MessageAddressee::class,'message_id');
//        return $this->hasMany(MessageAddressee::class,'message_id');
    }

    Public function getToUsersAttribute($name)
    {
        Return array_values(json_decode($name, true) ?: []);
    }

    Public function setToUsersAttribute($name)
    {
        $this->attributes['to_users'] = json_encode(array_values($name));
    }
}

// Жилину и Татьяне
