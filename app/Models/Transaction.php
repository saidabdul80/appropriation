<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        "owner_id",
        "owner_type",
        "action",
        "amount",
        "appropriation_history_id",
        "data",
        "account_type",
        "source",
        "description",
        "performed_by",
    ];
    protected $casts = [
        'data'=>AsArrayObject::class,
        'created_at'=>'datetime:Y-m-d',
        'updated_at'=>'datetime:Y-m-d'
    ];

    protected $table = "transactions";

    public function owner(){
        return $this->morphTo();
    }
}
