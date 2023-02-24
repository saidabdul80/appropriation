<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;
    protected $with = ['source'];
    protected $fillable= [
        "owner_id",
        "wallet_number",
        "owner_type",
        "balance",
        "safe_balance",
        "description",
        "source_id",
        "total_collection",
        "fund_category",
        "token"
    ];  

    public function owner(){
        return  $this->morphTo();
    }

    public function appropriation(){
        return  $this->belongsTo(Appropriation::class,'owner_id','id');
    }

    public function source(){
        return $this->belongsTo(Source::class);
    }
}
