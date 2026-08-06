<?php

namespace App\Models;
use App\Models\Rubric;
use App\Models\Milestone;
use Illuminate\Database\Eloquent\Model;

class RubricCriteria extends Model
{
    protected $table = 'rubric_criteria';

    protected $fillable = ['rubric_id', 'criteria_name', 'weight', 'max_score'];

    public function rubric()
    {
        return $this->belongsTo(Rubric::class);
    }
}