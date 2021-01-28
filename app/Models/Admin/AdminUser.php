<?php


namespace App\Models\Admin;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Admin\AdminUser
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property string|null $color
 * @property int|null $city
 * @property string|null $avatar
 * @property string|null $phone
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $active
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\AdminUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\AdminUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\AdminUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\AdminUser whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\AdminUser whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\AdminUser whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\AdminUser whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\AdminUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\AdminUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\AdminUser whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\AdminUser wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\AdminUser wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\AdminUser whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\AdminUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\AdminUser whereUsername($value)
 * @mixin \Eloquent
 * @property string|null $text_color
 * @property int $shift
 * @property int $timeline
 * @property int|null $salary
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\AdminUser whereSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\AdminUser whereShift($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\AdminUser whereTextColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\AdminUser whereTimeline($value)
 * @property int $percent
 * @property int $percent_limit
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\AdminUser wherePercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\AdminUser wherePercentLimit($value)
 * @property int $task_admin
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Admin\AdminUser whereTaskAdmin($value)
 */
class AdminUser extends Model
{

}
