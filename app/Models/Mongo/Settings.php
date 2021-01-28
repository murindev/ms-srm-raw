<?php


namespace App\Models\Mongo;


//use Jenssegers\Mongodb\Eloquent\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Mongo\Settings
 *
 * @property int $id
 * @property int $manager
 * @property string|null $type
 * @property string|null $settings
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Settings newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Settings newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Settings query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Settings whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Settings whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Settings whereManager($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Settings whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Settings whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Settings whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Settings extends Model
{
//    protected $connection = 'mongodb';
//    protected $collection = 'settings';
    protected $guarded = [];
}
