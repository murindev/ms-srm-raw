<?php


namespace App\Models\Finance;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Finance\Finance
 *
 * @property int $id
 * @property int|null $active
 * @property int $amount Сумма
 * @property int $form Н / БНал
 * @property int $type Дебет/Кредит
 * @property int|null $lid_id
 * @property int|null $act_id
 * @property int|null $customer Клиент
 * @property int|null $manager
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Finance\Finance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Finance\Finance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Finance\Finance query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Finance\Finance whereActId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Finance\Finance whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Finance\Finance whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Finance\Finance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Finance\Finance whereCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Finance\Finance whereForm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Finance\Finance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Finance\Finance whereLidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Finance\Finance whereManager($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Finance\Finance whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Finance\Finance whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Finance extends Model
{
    protected $guarded = [];
    public $searchable = [
      'id',
      'amount',
      'form',
      'type',
      'lid_id',
      'act_id',
      'customer',
      'manager',
      'created_at',
      'updated_at',
    ];

    public function getActIdAttribute($value){
        $res = [];
        foreach (explode(',', $value) as $v) if($v){
            $res[] = (int)$v;
        }
        return  $res;
    }

    public function setActIdAttribute($value){
        $this->attributes['act_id'] =  implode(',', $value);
    }




}
