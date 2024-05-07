<?php

namespace App\Models;

use App\Traits\Account;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use stdClass;

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

    public function getFundCategoriesAttribute(){
        $ids = Appropriation::where('scheme_id',$this->id)->pluck('id')->toArray();
        $wallet_details = Wallet::selectRaw('SUM(balance) as balance, SUM(total_collection) as total_collection, fund_category')->whereIn('owner_id',$ids)->where(['owner_type'=>'App\\Models\\Appropriation'])->groupBy('fund_category')->get();

        return $wallet_details->count() >0? $wallet_details->groupBy('fund_category'):new stdClass();
    }

    public function getTotalCollectionAttribute(){
        return $this->wallet?->total_collection;
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['wallet_number', 'owner_id','owner_type','amount']);
        // Chain fluent methods for configuration options
    }

/*     public function getFundCategoriesAttribute(){
        return FundCategory::where([
                    'scheme_id'=>$this->id
                ])->get();
    }
  */
    protected $appends = ['balance','total_collection','appropriation_wallets','funds','fund_categories'];

}
