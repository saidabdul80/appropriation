<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubheadBudgetItemName extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'created_at',
        'output',
        'outcome',
        'updated_at',
    ];

    public function subheadBudgetItems()
    {
        return $this->hasMany(SubheadBudgetItem::class, 'item_name_id');
    }

}
