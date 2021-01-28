<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Store\Equipment
 *
 * @property int $id
 * @property \App\Models\Helper\Equipment $name
 * @property string $inv_nr
 * @property string $start_date
 * @property int $operable
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store\Equipment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store\Equipment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store\Equipment query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store\Equipment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store\Equipment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store\Equipment whereInvNr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store\Equipment whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store\Equipment whereOperable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store\Equipment whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store\Equipment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Equipment extends Model
{
    protected $table = 'store_equipments';
    public function name(){
        return $this->hasOne(\App\Models\Helper\Equipment::class,'name');
    }
}
