<?php


namespace App\Models;



use Illuminate\Database\Eloquent\Model;


class DepartmentRoleReference extends Model
{
    protected $table = "dept_role_refs";
    public $timestamps = true;


    public function department(){
        return $this->hasOne(Department::class, 'id','dept_id');
    }
    public function departmentRole(){
        return $this->hasOne(DepartmentRole::class, 'id','dept_role_id');
    }


}
