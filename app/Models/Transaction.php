<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $with = ['source'];
    protected $fillable = [
        "owner_id",
        "owner_type",
        "action",
        "amount",
        "appropriation_history_id",
        "data",
        "account_type",
        "source_id",
        "description",
        "performed_by",
        "fund_category"
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

    public function source(){
        return $this->belongsTo(Source::class);
    }

    public function getDateAddedAttribute()
    {
        $carbonDate = Carbon::parse($this->created_at);

        // Check if the date is today
        if ($carbonDate->isToday()) {
            return $carbonDate->diffForHumans();
        }

        // Check if the date is yesterday
        if ($carbonDate->isYesterday()) {
            return 'Yesterday';
        }

        // Return the actual date for older dates
        return $carbonDate->toFormattedDateString();
    }

    public function getDateUpdatedAttribute()
    {
        $carbonDate = Carbon::parse($this->updated_at);

        // Check if the date is today
        if ($carbonDate->isToday()) {
            return $carbonDate->diffForHumans();
        }

        // Check if the date is yesterday
        if ($carbonDate->isYesterday()) {
            return 'Yesterday';
        }

        // Return the actual date for older dates
        return $carbonDate->toFormattedDateString();
    }

    protected $appends = [
        'date_added',
        'date_updated'
    ];
}
