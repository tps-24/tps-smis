<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'priority',
        'due_date',
    ];
    public function staff()
    {
        return $this->belongsToMany(Staff::class)
            ->withPivot('assigned_at', 'start_time', 'end_time', 'is_active', 'region', 'district')
            ->withTimestamps();
    }

}
