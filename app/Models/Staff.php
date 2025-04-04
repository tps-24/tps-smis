<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Staff extends Model
{

    use HasRoles; 

    protected $fillable = [
            'forceNumber',
            'rank',
            'nin',
            'firstName',
            'middleName',
            'lastName',
            'gender',
            'DoB',
            'maritalStatus',
            'religion',
            'tribe',
            'phoneNumber',
            'email',
            'currentAddress',
            'permanentAddress',
            'department_id',
            'designation',
            'educationLevel',
            'contractType',
            'joiningDate',
            'location',
            'user_id',
            'created_by',
            'updated_by'
        ];


        public function department() 
        { 
            return $this->belongsTo(Department::class); 

        }
        public function company() 
        { 
            return $this->belongsTo(Company::class); 
        }
        
        public function user()
        {
            return $this->belongsTo(User::class);
        }

        
}
