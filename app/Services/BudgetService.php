<?php
namespace App\Services;

use App\Models\SubHeadBudget;
use App\Models\Transaction;

class BudgetService
{
    /**
     * Check if the transaction can be processed without exceeding the budget.
     *
     * @param int $appropriationId
     * @param int $subheadId
     * @param float $oldAmount
     * @param float $newAmount
     * @throws \Exception
     */
    public static function validateTransaction($appropriationId, $subheadId, $oldAmount, $newAmount, $fundCategory)
    {
        if(empty($subheadId)){
            throw new \Exception('Invalid Tranx: Subhead required');
        }
        
        $subheadBudget = SubHeadBudget::getAmount($appropriationId, $subheadId);
        $spent = Transaction::sumAmountForSubhead($appropriationId, $subheadId, $fundCategory);
        $left = $subheadBudget - $spent;
                
        if (($left + $oldAmount) < $newAmount) {
            throw new \Exception('Insufficient balance');
        }
    }
}
