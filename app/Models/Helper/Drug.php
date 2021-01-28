<?php

namespace App\Models\Helper;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Helper\Drug
 *
 * @property int $id
 * @property string $name
 * @property string $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Drug newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Drug newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Drug query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Drug whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Drug whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Drug whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Drug whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Drug whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Drug extends Model
{
    protected $table = 'helper_drugs';
}
