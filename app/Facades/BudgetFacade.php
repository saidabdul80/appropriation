<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class BudgetFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'BudgetService'; // This should match the binding in the service provider
    }
}
