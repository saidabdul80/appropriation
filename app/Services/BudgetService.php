<?php
namespace App\Services;

use App\Models\SubHeadBudget;
use App\Models\SubheadBudgetItem;
use App\Models\Transaction;

class BudgetService
{
    /**
     * Check if the transaction can be processed without exceeding the budget.
     *
     * @param int $appropriationId
     * @param int $subheadItemId
     * @param float $oldAmount
     * @param float $newAmount
     * @throws \Exception
     */
    public static function validateTransaction($subheadItemId, $oldAmount, $newAmount)
    {
        if(empty($subheadItemId)){
            throw new \Exception('Invalid Tranx: Subhead required');
        }

        $subheadBudget = SubheadBudgetItem::getAmount($subheadItemId);
//        throw new \Exception($subheadBudget);
        $spent = Transaction::sumAmountForSubheadItem($subheadItemId);
        $left = $subheadBudget - $spent;

        if (($left + $oldAmount) < $newAmount) {
            throw new \Exception('Insufficient balance');
        }
    }
}
