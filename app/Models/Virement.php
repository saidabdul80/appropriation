<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Virement extends Model
{
    use HasFactory;

    protected $fillable=[
        'owner_type',
        'source_id',
        'destination_id',
        'amount',
        'created_by',
    ];

    public function getDestinationAttribute(){
        if($this->owner_type == 'SubheadBudgetItem'){
            return SubheadBudgetItem::find($this->destination_id)?->subhead_item;
        }

        if($this->owner_type == 'Subhead'){
            return SubHeadBudget::find($this->destination_id)?->subhead;
        }
    }

    public function getCreatorAttribute(){
        return User::find($this->created_by)?->first_name;
    }


    protected $appends = ['destination','creator'];

}
