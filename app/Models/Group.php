<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = [
        'group_name',
        'capstone_title',
        'adviser_id',
        'section_id',
        'room_id',
        'capstone_stage_id',
        'is_archived',
        'archived_year',
    ];

    protected $casts = [
        'is_archived' => 'boolean',
    ];

    public function capstoneStage()
    {
        return $this->belongsTo(CapstoneStages::class, 'capstone_stage_id');
    }

    public function room()
    {
        return $this->belongsTo(EvaluationRoom::class, 'room_id');
    }

    public function adviser()
    {
        return $this->belongsTo(Teacher::class, 'adviser_id');
    }

    public function students()
{
    return $this->belongsToMany(
        Student::class,
        'team_members',
        'group_id',   // foreign pivot key (team_members.group_id → groups.id)
        'user_id',    // related pivot key (team_members.user_id → students.user_id)
        'id',         // parent key (groups.id)
        'user_id'     // related key (students.user_id) ← this was missing
    );
}

    public function team_members()
{
    return $this->hasMany(TeamMember::class, 'group_id');
}

public function groupMilestones()
{
    return $this->hasMany(GroupMilestones::class, 'group_id');
}
public function section()
{
    return $this->belongsTo(Section::class, 'section_id');
}
}