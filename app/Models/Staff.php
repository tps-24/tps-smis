<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
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
            'nextofkinFullname',
            'nextofkinRelationship',
            'nextofkinPhoneNumber',
            'nextofkinPhysicalAddress',
            'user_id',
            'created_by'
        ];


        public function department() 
        { 
            return $this->belongsTo(Department::class); 
        }
}
