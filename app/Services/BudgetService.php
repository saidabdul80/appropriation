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
    public static function validateTransaction($appropriationId, $subheadItemId, $oldAmount, $newAmount, $fundCategory)
    {
        if(empty($subheadItemId)){
            throw new \Exception('Invalid Tranx: Subhead required');
        }

        $subheadBudget = SubheadBudgetItem::getAmount($subheadItemId);
        $spent = Transaction::sumAmountForSubheadItem($appropriationId, $subheadItemId, $fundCategory);
        $left = $subheadBudget - $spent;

        if (($left + $oldAmount) < $newAmount) {
            throw new \Exception('Insufficient balance');
        }
    }
}
