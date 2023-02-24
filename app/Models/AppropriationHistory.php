<?php

namespace App\Models;

use App\Casts\FundMonthYearToFullDate;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppropriationHistory extends Model
{
    use HasFactory;
    protected $with = ['source'];
    protected $fillable= [
        "owner_id",
        "owner_type",
        "amount",
        "appropriation",
        "source_id",
        "description",
        "fund_category",
        "fund_month_year"
    ];
    protected $table = "appropriations_history";
    protected $casts = [
        'appropriation'=>AsArrayObject::class,        
        'created_at'=>'datetime:Y-m-d',
        "fund_month_year"=>FundMonthYearToFullDate::class
    ];

    public function source(){
        return $this->belongsTo(Source::class);
    }
    
}
