<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Store\ImplementEquipment
 *
 * @property int $id
 * @property int|null $master
 * @property int|null $equipment
 * @property int|null $equipment_name_id
 * @property string|null $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store\ImplementEquipment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store\ImplementEquipment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store\ImplementEquipment query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store\ImplementEquipment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store\ImplementEquipment whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store\ImplementEquipment whereEquipment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store\ImplementEquipment whereEquipmentNameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store\ImplementEquipment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store\ImplementEquipment whereMaster($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store\ImplementEquipment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ImplementEquipment extends Model
{
    protected $table = 'implements_equipments';
}
