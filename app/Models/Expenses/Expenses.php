<?php


namespace App\Models\Expenses;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Expenses\Expenses
 *
 * @property int $id
 * @property string $type
 * @property int $ref
 * @property int $value
 * @property string $date
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expenses\Expenses newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expenses\Expenses newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expenses\Expenses query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expenses\Expenses whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expenses\Expenses whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expenses\Expenses whereRef($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expenses\Expenses whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expenses\Expenses whereValue($value)
 * @mixin \Eloquent
 * @property int|null $cash
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expenses\Expenses whereCash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expenses\Expenses whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Expenses\Expenses whereUpdatedAt($value)
 */
class Expenses extends Model
{
    protected $table = 'expenses';
    protected $fillable = ['type','ref','value','date'];
}
