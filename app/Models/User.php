<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'sessionProgramme_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function student(){
        return $this->hasOne(Student::class,'user_id','id');
    }
    public function staff(){
        return $this->hasOne(Staff::class,'user_id','id');
    }
    public function sessionProgramme() 
    { 
        return $this->belongsTo(SessionProgramme::class); 
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    

    public function programmeCourseSemesters()
    {
        return $this->belongsToMany(ProgrammeCourseSemester::class, 'course_instructors', 'user_id', 'programme_course_semester_id')
                    ->withPivot('course_id');
    }
}
