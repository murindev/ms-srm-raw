<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Settings
 *
 * @property int $id
 * @property int $manager
 * @property string|null $type
 * @property string|null $settings
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereManager($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Settings whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Settings extends Model
{
    protected $table = 'settings';
    protected $fillable = [
        'type',
        'manager',
        'settings',
    ];
    protected $guarded = [];

//    public function getSettingsAttribute($settings)
//    {
//        return array_values(json_decode($settings, true) ?: []);
//    }
//
//    public function setSettingsAttribute($settings)
//    {
//        $this->attributes['settings'] = json_encode(array_values($settings));
//    }
}
