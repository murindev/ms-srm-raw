<?php

namespace App\Models\Messenger;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Messenger\MessageAddressee
 *
 * @property int $id
 * @property int $message_id
 * @property int $user
 * @property int $seen
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Messenger\Message $message
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messenger\MessageAddressee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messenger\MessageAddressee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messenger\MessageAddressee query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messenger\MessageAddressee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messenger\MessageAddressee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messenger\MessageAddressee whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messenger\MessageAddressee whereSeen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messenger\MessageAddressee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Messenger\MessageAddressee whereUser($value)
 * @mixin \Eloquent
 */
class MessageAddressee extends Model
{
    protected $fillable = ['message_id','user','seen','created_at','updated_at'];

    public function message(){
        return $this->belongsTo(Message::class);
    }
}
