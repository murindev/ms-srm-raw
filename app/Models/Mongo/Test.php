<?php


namespace App\Models\Mongo;

//use Jenssegers\Mongodb\Eloquent\Model as Eloquent;


use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Mongo\Test
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Test newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Test newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Mongo\Test query()
 * @mixin \Eloquent
 */
class Test extends Model
{

//    protected $connection = 'mongodb';
//    protected $collection = 'articles';
    protected $guarded = [];
//    protected $fillable = ['title','text'];
}

