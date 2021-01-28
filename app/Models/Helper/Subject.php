<?php

namespace App\Models\Helper;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Helper\Subject
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Subject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Subject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Subject query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Subject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Subject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Subject whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Subject whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Subject extends Model
{
    protected $table = 'helper_subjects';
}
