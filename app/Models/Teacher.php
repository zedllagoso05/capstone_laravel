<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'user_id',
        'teacher_first_name',
        'teacher_middle_name',
        'teacher_last_name',
        'teacher_email',
        'contact_number',
    ];

    /**
     * Get the user account associated with the teacher.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get the groups advised by this teacher.
     */
    public function groups()
    {
        return $this->hasMany(Group::class, 'adviser_id');
    }
    public function evaluations()
{
    return $this->hasMany(Evaluation::class, 'teacher_id');
}
public function sections()
{
    return $this->hasMany(Section::class, 'user_id', 'user_id');
}
public function evaluationRooms()
{
    return $this->belongsToMany(EvaluationRoom::class, 'room_panelists', 'teacher_id', 'room_id')
        ->withTimestamps();
}
}