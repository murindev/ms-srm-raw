<?php

namespace App\Models\Helper;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Helper\Pest
 *
 * @property int $id
 * @property int $sort
 * @property int $active
 * @property string|null $name
 * @property string|null $methods
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Pest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Pest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Pest query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Pest whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Pest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Pest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Pest whereMethods($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Pest whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Pest whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Pest whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Pest extends Model
{
    protected $table = 'helper_pests';

    public function getMethodsAttribute($value)
    {
        return explode(',', $value);
    }

    public function setMethodsAttribute($value)
    {
        $this->attributes['methods'] = implode(',', $value);
    }









}
