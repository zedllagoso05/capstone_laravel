<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    protected $fillable = ['milestone_title', 'milestone_description','start_date' ,'due_date', 'step_order','capstone_stage_id'];
    
    public function rubrics()
{
    return $this->hasMany(Rubric::class);
}

    public function capstoneStage()
    {
        return $this->belongsTo(CapstoneStages::class, 'capstone_stage_id');
    }
}