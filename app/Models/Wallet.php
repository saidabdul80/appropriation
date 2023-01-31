<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;
    protected $fillable= [
        "owner_id",
        "wallet_number",
        "owner_type",
        "balance",
        "safe_balance",
        "description",
        "source",
        "total_collection"
    ];  

    public function owner(){
        return  $this->morphTo();
    }
}
