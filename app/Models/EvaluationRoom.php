<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EvaluationRoom extends Model
{
    protected $fillable = ['room_name', 'join_code', 'required_milestone_id', 'activity_name'];

    protected static function booted()
    {
        static::creating(function ($room) {
            if (empty($room->join_code)) {
                $room->join_code = self::generateUniqueCode();
            }
        });
    }

    public static function generateUniqueCode(): string
    {
        do {
            $code = strtoupper(Str::random(6));
        } while (self::where('join_code', $code)->exists());

        return $code;
    }

    public function requiredMilestone()
    {
        return $this->belongsTo(Milestone::class, 'required_milestone_id');
    }

    public function panelists()
    {
        return $this->belongsToMany(Teacher::class, 'room_panelists', 'room_id', 'teacher_id');
    }

    public function groups()
    {
        return $this->hasMany(Group::class, 'room_id');
    }
}