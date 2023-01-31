<?php

namespace App\Models;

use App\Traits\Account;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $fillable =[        
        "name",
        "short_name",
    ];
}
