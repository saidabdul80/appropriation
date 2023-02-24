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
    protected $with = ['appropriations','sources'];
    protected $fillable = ['name',"wallet_number"];
    public function appropriations(){        
        return $this->hasMany(Appropriation::class);
    }

    public function getAppropriationWalletsAttribute(){        
     /*    $ids = Appropriation::where('scheme_id',$this->id)->pluck('id')->toArray();        
        $wallets = Wallet::with('appropriation')->whereIn('owner_id',$ids)->where('owner_type','App\\Models\\Appropriation')->get()->groupBy('fund_category')->map(function($item, $key){
            return $item->map(function($t){
                return collect($t)->merge($t->appropriation);
            });        
        });
        dd(json_encode($wallets)); */
    }

    public function wallet()
    {
        return  $this->morphOne(Wallet::class,'owner');
    }

    public function transactions()
    {
        return  $this->hasMany(Transaction::class);
    }    

    public function sources(){
       return $this->hasMany(Source::class);
    }

    public function getFundsAttribute(){
        return Fund::where(['scheme_id'=>$this->id,'status'=>'unused'])->get();
    }

    public function getBalanceAttribute(){
        $ids = Appropriation::where('scheme_id',$this->id)->pluck('id')->toArray();
        $balance = Wallet::whereIn('owner_id',$ids)->where(['owner_type'=>'App\\Models\\Appropriation'])->sum('balance');
        return $balance;
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

    public function getAppropriationBalanceAttribute(){
        $appropriations = Appropriation::where('scheme_id',$this->id)->get();
        /* foreach($appropriations as $key => $appropriation){
            $total_balance = Wallet::where(['owner_id'=>$appropriation->id, 'owner_type'=>Appropriation::class])->sum('balance');            
            $appropriation->total_balance = $total_balance;
        } */
        return $appropriations;

    }

    protected $appends = ['balance','total_collection','appropriation_wallets','funds', 'appropriation_balance'];
    
}
