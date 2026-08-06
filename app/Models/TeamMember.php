<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'team_members';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'group_id',
        'user_id',
        'role',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ─────────────────────────────────────────────────────
    // Relationships
    // ─────────────────────────────────────────────────────

    /**
     * Get the group that the member belongs to.
     */
// TeamMember belongs to a student
public function student()
{
    return $this->belongsTo(Student::class, 'user_id', 'user_id');
}

// TeamMember belongs to a group
public function group()
{
    return $this->belongsTo(Group::class, 'group_id');
}
}