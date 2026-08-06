<?php

namespace App\Models;
use App\Models\RubricCriteria;
use Illuminate\Database\Eloquent\Model;

class Rubric extends Model
{
    protected $fillable = ['rubric_name', 'milestone_id'];

    public function milestone()
    {
        return $this->belongsTo(Milestone::class);
    }

    public function criteria()
    {
        return $this->hasMany(RubricCriteria::class,'rubric_id');
    }
}