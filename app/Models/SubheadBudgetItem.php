<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubheadBudgetItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name_id',
        'subhead_budget_id',
        'amount',
        'created_at',
        'updated_at',
    ];

    public function itemName()
    {
        return $this->belongsTo(SubheadBudgetItemName::class, 'item_name_id');
    }

    public function subheadBudget()
    {
        return $this->belongsTo(SubheadBudget::class);
    }

    public static function getAmount($id){
        return self::where(['id'=>$id])->first()?->amount;
    }

    public function getSubheadItemAttribute(){
        return SubheadBudgetItemName::find($this->item_name_id)?->name;
    }

    public function  getBalanceAttribute(){
        return $this->amount - Transaction::sumAmountForSubheadItem($this->id);
    }


    protected $appends = ['subhead_item','balance'];
}
