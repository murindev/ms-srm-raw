<?php

namespace App\Models\Helper;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Helper\Company
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Company newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Company query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Company whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Company whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Company whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Company extends Model
{
    protected $table = 'helper_companies';
}
