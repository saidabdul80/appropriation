<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppropriationHistory extends Model
{
    use HasFactory;
    protected $fillable= [
        "owner_id",
        "owner_type",
        "amount",
        "appropriation",
        "source",
        "description",
        "fund_category"
    ];
    protected $table = "appropriations_history";
    protected $casts = [
        'appropriation'=>AsArrayObject::class,
        'fund_category'=>AsArrayObject::class,
        'created_at'=>'datetime:Y-m-d'
    ];
    
}
