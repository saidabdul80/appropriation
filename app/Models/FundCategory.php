<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'scheme_id',
        'fund_category',
        'fund_month_year'
    ];

}
