<?php


namespace App\Models\Helper;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Helper\Expense
 *
 * @property int $id
 * @property string|null $name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Expense newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Expense newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Expense query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Expense whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Expense whereName($value)
 * @mixin \Eloquent
 * @property string $type
 * @property string|null $created_at
 * @property string|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Expense whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Expense whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Helper\Expense whereUpdatedAt($value)
 */
class Expense extends Model
{
    protected $table = 'helper_expenses';
    protected $fillable = ['name','type'];
    public $timestamps = false;

}
