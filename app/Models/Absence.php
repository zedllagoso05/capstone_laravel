<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absence extends Model
{
        protected $table = 'absences';

    protected $fillable = [
    'group_id',
    'milestone_id',
    'user_id',

    ];


    
        public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    // Each evaluation belongs to one milestone
    public function milestone(): BelongsTo
    {
        return $this->belongsTo(Milestone::class);
    }
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
