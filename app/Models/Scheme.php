<?php

namespace App\Models;

use App\Traits\Account;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
class Scheme extends Model
{
    use HasFactory, LogsActivity, Account;
    protected $with = ['appropriations'];
    protected $fillable = ['name',"wallet_number"];
    public function appropriations(){        
        return $this->hasMany(Appropriation::class);
    }

    public function wallet()
    {
        return  $this->morphOne(Wallet::class,'owner');
    }

    public function transactions()
    {
        return  $this->hasMany(Transaction::class);
    }    

    public function getBalanceAttribute(){
        return $this->wallet->balance;
    }

    public function getTotalCollectionAttribute(){
        return $this->wallet->total_collection;
    }
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['wallet_number', 'owner_id','owner_type','amount']);
        // Chain fluent methods for configuration options
    }

    protected $appends = ['balance','total_collection'];
    
}
