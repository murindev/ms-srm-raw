<?php

namespace App\Models\Helper;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Helper\Region
 *
 * @property int $id
 * @property string|null $region
 * @property string|null $area
 * @property string|null $city
 * @property int|null $code
 * @property string|null $phone
 * @property string|null $center_lat
 * @property string|null $center_lon
 * @property int $zoom
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Helper\Phone[] $phones
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Region newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Region newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Region query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Region whereArea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Region whereCenterLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Region whereCenterLon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Region whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Region whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Region whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Region whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Region wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Region whereRegion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Region whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Region whereZoom($value)
 * @mixin \Eloquent
 */
class Region extends Model
{
    protected $table = 'helper_regions';

    public function phones(){
        return $this->hasMany(Phone::class,'region');
    }
    public function cities(){
        $cities = $this::all()->toArray();
        return array_column($cities, NULL, 'city');
    }
    public function regions(){
        $cities = $this::all()->toArray();
        return array_column($cities, NULL, 'region');
    }
}
