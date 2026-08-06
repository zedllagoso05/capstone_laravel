<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',        // teacher's id (foreign key to teachers.id)
        'section_name',
    ];

    /**
     * A section belongs to one teacher (the adviser).
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'user_id');
        // 'user_id' is the foreign key on the sections table
        // that points to the teachers table's 'id' column
    }

    /**
     * Optional: if you later link sections to groups or students.
     */
    public function groups()
    {
        return $this->hasMany(Group::class);
    }
}