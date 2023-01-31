<?php
namespace App\Traits;
/**
 * 
 */
use App\Models\Wallet;

trait Account
{
    public function wallet()
    {
        return  $this->morphOne(Wallet::class,'owner');
    }
    

}


?>