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

    protected $with = ['virments'];

    public function itemName()
    {
        return $this->belongsTo(SubheadBudgetItemName::class, 'item_name_id');
    }

    public function subheadBudget()
    {
        return $this->belongsTo(SubheadBudget::class);
    }

    public static function getAmount(int $id){
        return self::where(['id'=>$id])->first()?->amount;
    }

    public static function usedAmount(int $id){
        return Transaction::where('subhead_item_id',$id)->sum('amount');
    }

    public static function getItemsAmount(int $subhead_budget_id){
            return self::where(['subhead_budget_id'=>$subhead_budget_id])->sum('amount');
    }

    public static function getAmountBalance(int $id){
        return self::where(['id'=>$id])->first()?->amount - Transaction::sumAmountForSubheadItem($id);;
    }

    public function getSubheadItemAttribute(){
        return SubheadBudgetItemName::find($this->item_name_id)?->name;
    }

    public function  getBalanceAttribute(){
        return $this->amount - Transaction::sumAmountForSubheadItem($this->id);
    }

    public function virments(){
        return $this->hasMany(Virement::class,'source_id');
    }

    protected $appends = ['subhead_item','balance'];
}
