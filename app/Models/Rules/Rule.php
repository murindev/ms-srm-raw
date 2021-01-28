<?php

namespace App\Models\Rules;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Rules\Rule
 *
 * @property int $id
 * @property int|null $user_in_charge
 * @property int|null $user_auditor
 * @property int|null $rule
 * @property string|null $subject
 * @property int|null $subject_id
 * @property string|null $date_expected
 * @property string|null $date_applied
 * @property string $dates_diff
 * @property string|null $note
 * @property int $salt
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rules\Rule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rules\Rule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rules\Rule query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rules\Rule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rules\Rule whereDateApplied($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rules\Rule whereDateExpected($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rules\Rule whereDatesDiff($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rules\Rule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rules\Rule whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rules\Rule whereRule($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rules\Rule whereSalt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rules\Rule whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rules\Rule whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rules\Rule whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rules\Rule whereUserAuditor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rules\Rule whereUserInCharge($value)
 * @mixin \Eloquent
 * @property int|null $rule_id
 * @property int|null $lid_id
 * @property int|null $act_id
 * @property int|null $implement_id
 * @property-read \App\Models\Crm\Implement|null $implement
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rules\Rule whereActId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rules\Rule whereImplementId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rules\Rule whereLidId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Rules\Rule whereRuleId($value)
 */
class Rule extends Model
{
    protected $table = 'log_rules';
    protected $fillable = [
        'user_in_charge',
        'user_auditor',
        'subject',
        'rule_id',
        'subject_id',
        'date_expected',
        'date_applied',
        'dates_diff',
        'note',
        'salt',
        'lid_id',
        'act_id',
        'implement_id'
    ];

    public function implement(){
        return $this->belongsTo(\App\Models\Crm\Implement::class,'implement_id');
    }
}
