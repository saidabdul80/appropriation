<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'department_id'];
    public function getDepartmentNameAttribute(){
        return Department::find($this->department_id)?->short_name;
    }

    protected $appends = ['department_name'];
}
