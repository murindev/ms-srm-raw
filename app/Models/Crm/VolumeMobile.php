<?php

namespace App\Models\Crm;

use App\Models\Helper\Method;
use App\Models\Helper\Pest;
use App\Models\Helper\Square;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Crm\Volume
 *
 * @property int $id
 * @property int|null $parent
 * @property int|null $lid_id
 * @property int|null $pest
 * @property float|null $square
 * @property int|null $entity
 * @property string|null $method
 * @property float|null $price_standard
 * @property float|null $price_fact
 * @property float|null $kpi
 * @property string|null $note
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Crm\Act|null $act
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Volume newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Volume newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Volume query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Volume whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Volume whereEntity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Volume whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Volume whereKpi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Volume whereLidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Volume whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Volume whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Volume whereParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Volume wherePest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Volume wherePriceFact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Volume wherePriceStandard($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Volume whereSquare($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Volume whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property float|null $square_fact
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Crm\Volume whereSquareFact($value)
 */
class VolumeMobile extends Model
{
    protected  $table = 'volumes';
    protected $guarded = [];
    public $fillable = ['parent', 'lid_id', 'statistic_id', 'pest', 'square', 'square_fact', 'entity', 'method', 'price_standard', 'price_fact', 'kpi', 'note', 'created_at', 'updated_at',];
    public $searchable = ['parent', 'lid_id','statistic_id', 'pest', 'square', 'square_fact', 'entity', 'method', 'price_standard', 'price_fact', 'kpi', 'note', 'created_at', 'updated_at',];
    public function act(){
        return $this->belongsTo(Act::class,'parent');
    }
    public function statistic(){
        return $this->belongsTo(LidStatistic::class,'statistic_id');
    }

    public function getMethodAttribute($value)
    {
        $res = [];
        foreach (explode(',', $value) as $v) if($v){
            $res[] = (int)$v;
        }
        return Method::whereIn('id',$res)->get();
    }

    public function setMethodAttribute($value)
    {
        $this->attributes['method'] =  implode(',', $value);

    }






    public function pestRow(){
        return $this->hasOne(Pest::class,'id','pest');
    }
    public function squareEntityRow(){
        return $this->hasOne(Square::class,'id','entity');
    }


    // scopes

    public function scopeMethod($query,$method){
        return Method::whereIn('id',[28])->get();
    }

}












