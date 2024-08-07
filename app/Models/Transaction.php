<?php

namespace App\Models;

use App\Casts\TransactionData;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
        "fund_category",
        "subhead_id",
        'subhead_item_id',
        'fund_date'
    ];

    protected $casts = [
        'data'=>TransactionData::class,
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

    public static function sumAmountForSubhead($appropriationId, $subheadId, $fund_category)
    {
        return self::where([
            'owner_id' => $appropriationId,
            'owner_type' => 'appropriation',
            'subhead_id' => $subheadId,
            'fund_category'=> $fund_category
        ])->sum('amount');
    }


    protected function fundDate(): Attribute
    {
        return Attribute::make(
            get: fn ( $value) =>  empty($value)?'':  Carbon::parse($value)->format('d F, Y'),
            set: fn ( $value) => $value,
        );
    }

    public static function sumAmountForSubheadItem($subheadItemId)
    {
        return self::where([
            'subhead_item_id' => $subheadItemId,
        ])->sum('amount');
    }

    public function getSubheadAttribute(){
        return SubHeadBudget::find($this->subhead_id)?->subhead;
    }

    public function getSubheadItemAttribute(){
        return SubheadBudgetItem::find($this->subhead_item_id)?->subhead_item;
    }

    public function getOutcomeAttribute(){
        return SubheadBudgetItem::find($this->subhead_item_id)?->outcome;
    }
    public function getOutputAttribute(){
        return SubheadBudgetItem::find($this->subhead_item_id)?->output;
    }

    public function getHeadAttribute(){
        return SubHeadBudget::find($this->subhead_id)?->head;
    }

    protected $appends = [
        'date_added',
        'date_updated',
        'head',
        'subhead',
        'subhead_item',
        'outcome',
        'output'
    ];
}
