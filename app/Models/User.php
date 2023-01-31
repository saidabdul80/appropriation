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
        "email",
        "first_name",
        "surname",
        "department_id",
        "unit_id",
        "gender",
        "phone_number",
        "department_id",
        "unit_id",
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
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getTaskIdsAttribute(){
        $id =  $this->id;
        return collect(DB::select(DB::raw("SELECT id FROM `tasks` WHERE JSON_CONTAINS(members,JSON_OBJECT('id', $id))")))->pluck('id');
    }

    public function getUnitAttribute(){
        return Unit::find($this->unit_id)?->short_name;
    }

    public function getDepartmentAttribute(){
        return Department::find($this->department_id)?->short_name;
    }

    public function getUserRolesAttribute(){
        
        return $this->getRoleNames();// DB::table('roles')->whereIn('id', DB::table('model_has_roles')->where('model_id',$this->id)->pluck('role_id'))->pluck('name');
    }

    public function getRoleIdsAttribute(){        
        return DB::table('model_has_roles')->where('model_id',$this->id)->pluck('role_id');
    }

    public function getPermissionIdsAttribute(){        
        return DB::table('model_has_permissions')->where('model_id',$this->id)->pluck('permission_id');
    }

    public function getViewedTasksAttribute(){
        return ViewedTask::where('user_id', $this->id)->pluck('task_id');
    }   

    public function getTodayTasksAttribute(){        
        return Task::whereIn('id',Accomplisher::whereDate('created_at', Carbon::today())->where('user_id', $this->id)->pluck('task_id'))->get();
    }

    public function getCompletedAttribute(){        
        return Task::whereIn('id',Accomplisher::whereDate('created_at', Carbon::today())->where('user_id', $this->id)->pluck('task_id'))->where('completed', 'yes')->count();
    }

    protected $appends = ['completed', 'today_tasks','viewed_tasks','task_ids','unit','department','user_roles','role_ids','permission_ids'];
}
