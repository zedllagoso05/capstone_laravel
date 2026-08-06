<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluation extends Model
{
    protected $fillable = [
        'group_id',
        'milestone_id',
        'student_id',
        'teacher_id',
        'score',
        'max_score',
        'feedback',
        'evaluation_date',
    ];

    protected $casts = [
        'evaluation_date' => 'date',
        'score' => 'decimal:2',
        'max_score' => 'decimal:2',
    ];

    // Each evaluation belongs to one group
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    // Each evaluation belongs to one milestone
    public function milestone(): BelongsTo
    {
        return $this->belongsTo(Milestone::class);
    }

    // Each evaluation belongs to one teacher
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    // Each evaluation belongs to one student
    // Note: student_id in evaluations is a varchar matching students.user_id or students.id
    // Adjust the foreign key and owner key as needed
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }
}