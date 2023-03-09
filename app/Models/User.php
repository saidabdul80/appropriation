<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Contracts\Role;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles,HasPermissions;
    protected $with=['roles','permissions'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "email_address",
        "first_name",
        "surname",
        "department_id",        
        "gender",
        "phone_number",           
        "password",
    ];

    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */   

     public function __construct() {
        $this->connection ='central_connection';
    }
 
    public function getDepartmentAttribute(){
       /*  $department_ids = explode(',',$this->roles()->pluck('department_ids')->join(','));
        return Department::whereIn('id',$department_ids)?->short_name; */
    }

    public function scopeSearch($query, $search)
    {
        if(!is_null($search)){
            return $query->where('first_name', 'like', '%' . $search . '%')
                ->orWhere('other_name', 'like', '%' . $search . '%')
                ->orWhere('surname', 'like', '%' . $search . '%')
                ->orWhere('phone_number', 'like', '%' . $search . '%')                
                ->orWhere('email_address', 'like', '%' . $search . '%')
                ->orWhere('nicare_code', 'like', '%' . $search . '%');
        }
    }

    public function getUserRolesAttribute(){        
        return $this->getRoleNames();// DB::table('roles')->whereIn('id', DB::table('model_has_roles')->where('model_id',$this->id)->pluck('role_id'))->pluck('name');
    }

    public function getRoleIdsAttribute(){        
       return $this->roles()->pluck('id');
    }

    public function getDepartmentIdsAttribute(){                
        return explode(',',$this->roles()->pluck('department_ids')->join(','));
    }

    public function getPermissionIdsAttribute(){        
       return $this->permissions()->pluck('id'); 
    }

    protected $appends = ['department','department_ids','user_roles','role_ids','permission_ids'];
}
