<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubHeadBudget extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'subhead_id',
        'appropriation_id',
        'fund_category',
        'amount',
    ];

    public function getSubheadAttribute(){
        return SubHead::find($this->subhead_id)?->name;
    }

    public static function getAmount($appropriationId, $subheadId){
        return self::where(['id'=>$subheadId])->first()?->amount;
    }

    public function  getBalanceAttribute(){
        return $this->amount -Transaction::sumAmountForSubhead($this->appropriation_id, $this->id, $this->fund_category);
    }

    protected $appends = ['subhead', 'balance'];
}
