<?php

namespace App\Models\Helper;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Helper\Phone
 *
 * @property int $id
 * @property string|null $region
 * @property string|null $owner
 * @property string|null $hint
 * @property string|null $phone
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Phone newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Phone newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Phone query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Phone whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Phone whereHint($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Phone whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Phone whereOwner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Phone wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Phone whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Phone whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Phone extends Model
{
    protected $table = 'helper_phones';

    protected $fillable = ['region','owner','hint','phone'];

//    public function region(){
//        return $this->belongsTo(Region::class,'region');
//    }
}
