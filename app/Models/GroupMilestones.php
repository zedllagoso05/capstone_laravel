<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupMilestones extends Model
{
    protected $fillable = [
        'group_id',
        'milestone_id',
        'status',
        'completion_date',
        'due_date',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function milestone()
    {
        return $this->belongsTo(Milestone::class);
    }
}