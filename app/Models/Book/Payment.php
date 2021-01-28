<?php

namespace App\Models\Book;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Book\Payment
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $lid_id
 * @property float|null $payment
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Book\Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Book\Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Book\Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Book\Payment whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Book\Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Book\Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Book\Payment whereLidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Book\Payment whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Book\Payment wherePayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Book\Payment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Payment extends Model
{}
