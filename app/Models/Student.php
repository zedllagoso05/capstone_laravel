<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';
    protected $fillable = [
        'user_id',
        'student_first_name',
        'student_middle_name',
        'student_last_name',
        'student_email',
        'contact_number',
        'course',
        'section',
        'is_archived',
        'capstone_year_id',
    ];

    protected $casts = [
        'is_archived' => 'boolean',
    ];

    public function capstoneYear()
    {
        return $this->belongsTo(CapstoneYear::class, 'capstone_year_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
public function teamMembers()
{
    return $this->hasMany(TeamMember::class, 'user_id', 'user_id');
}

public function groups()
{
    return $this->hasManyThrough(
        Group::class,
        TeamMember::class,
        'user_id',   // FK on team_members
        'id',        // FK on groups
        'user_id',   // local key on students
        'group_id'   // local key on team_members
    );
}
}