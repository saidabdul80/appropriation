<?php

namespace App\Models;

use App\Casts\ToArray;
use App\Traits\Account;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
class Appropriation extends Model
{
    use HasFactory;
    //protected $with = ["wallets"];
    protected $fillable= [
        "scheme_id",
        "department_id",
        'appropriation_type_id',
        "wallet_number",
        "percentage_dividend",
        "identifier"
    ];
    
    protected $casts = [
        "department_id"=>ToArray::class
    ];

    public function wallet()
    {
        return $this->hasOne(Wallet::class,'owner_id','id');
    }

    public function wallets()
    {
        return $this->hasMany(Wallet::class,'owner_id','id');
    }

    public function getMainWalletAttribute()
    {
        return MainWallet::where(['owner_id'=>$this->id,'owner_type'=>'appropriation'])->first();
    }

    public function getFundCategoriesAttribute()
    {
        return Wallet::where('owner_id',$this->id)->where('owner_type','App\\Models\\Appropriation')->pluck('fund_category');
    }

    //total balance accross all funds
    public function getBalanceAttribute(){
        return Wallet::where('owner_id', $this->id)->where('owner_type','App\\Models\\Appropriation')->sum('balance');
    }

    public function getTotalCollectionAttribute(){
        return Wallet::where('owner_id', $this->id)->where('owner_type','App\\Models\\Appropriation')->sum('total_collection');
    }

    /*public function department_ids(){
        return explode(',',$this->department_id);
       } 
    */

    public function getDepartmentAttribute(){        
       return implode(',', Department::whereIn("id", $this->department_id)->get()->pluck('short_name')->toArray());
    }

    public function getNameAttribute()
    {
        return   AppropriationType::find($this->appropriation_type_id)?->name;
    }
    protected $appends = ['total_collection','name','department', 'department_id','balance', 'main_wallet'];
    
}

