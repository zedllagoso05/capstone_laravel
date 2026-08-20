<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CapstoneYear extends Model
{
    protected $fillable = [
        'year',
        'is_active',
        'capstone_1_enabled',
        'capstone_2_enabled',
        'archived_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'capstone_1_enabled' => 'boolean',
        'capstone_2_enabled' => 'boolean',
        'archived_at' => 'datetime',
    ];

    public function groups()
    {
        return $this->hasMany(Group::class, 'capstone_year_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'capstone_year_id');
    }

    public function capstoneStages()
    {
        return $this->hasMany(CapstoneStages::class, 'capstone_year_id');
    }

    /**
     * Get the currently active CapstoneYear.
     * Fallback and auto-create if none exists.
     */
    public static function getActiveYear()
    {
        $active = self::where('is_active', true)->first();
        if (!$active) {
            $yearStr = date('Y') . '-' . (date('Y') + 1);
            $active = self::create([
                'year' => $yearStr,
                'is_active' => true,
                'capstone_1_enabled' => true,
                'capstone_2_enabled' => true,
            ]);
        }
        return $active;
    }
}
