<?php

namespace App\Models;

use App\Casts\ToArray;
use App\Traits\Account;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
class Appropriation extends Model
{
    use HasFactory, Account;
    protected $with = ["wallet"];
    protected $fillable= [
        "scheme_id",
        "department_id",
        "name",
        "wallet_number",
        "percentage_dividend"
    ];

    protected $casts = [
        "department_id"=>ToArray::class
    ];

/*     public function department_ids(){
        return explode(',',$this->department_id);
    } */
    public function getDepartmentAttribute(){        
       return implode(',', Department::whereIn("id", $this->department_id)->get()->pluck('short_name')->toArray());
    }

    protected $appends = ['department', 'department_id'];
    
}

