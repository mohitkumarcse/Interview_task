<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable
{

    protected $primaryKey = 'employee_id';
    protected $guarded = [];
    protected $hidden = ['password', 'remember_token'];


    public function departments()
    {
        return $this->belongsTo(Employee::class, 'department_id');
    }
}
