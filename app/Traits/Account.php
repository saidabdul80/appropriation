<?php
namespace App\Traits;
/**
 * 
 */
use App\Models\Wallet;

trait Account
{
    public function wallets()
    {
        return  $this->morphToMany(Wallet::class,'appropriation');
    }
    

}


?>