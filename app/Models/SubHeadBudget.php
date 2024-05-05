<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubHeadBudget extends Model
{
    use HasFactory, SoftDeletes;

    protected $with =["subheadBudgetItems"];
    protected $fillable = [
        'subhead_id',
        'appropriation_id',
        'fund_category',
        'amount',
    ];

    public function getSubheadAttribute(){
        return SubHead::find($this->subhead_id)?->name;
    }

    public function getHeadAttribute(){
        return Appropriation::find($this->appropriation_id)?->name;
    }


    public static function getAmount($id){
        return self::where(['id'=>$id])->first()?->amount;
    }

    public static function usedAmount($id){
        return Transaction::where('subhead_item_id', $id)->sum('amount');
    }


    public static function getBalanceAfterAllocation($id){
        return self::where(['id'=>$id])->first()?->amount - SubheadBudgetItem::where('subhead_budget_id', $id)->sum('amount');
    }



    public function  getBalanceAttribute(){
        return $this->amount - SubheadBudgetItem::getItemsAmount($this->id);
    }

    public function subheadBudgetItems()
    {
        return $this->hasMany(SubheadBudgetItem::class,'subhead_budget_id');
    }

    protected $appends = ['subhead', 'balance'];
}
