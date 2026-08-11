<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CapstoneStages extends Model
{
        public $timestamps = false;
        protected $fillable = ['id', 'stage_title', 'is_enabled', 'is_archived', 'archived_year', 'stage_type'];
        
        protected $casts = [
            'is_enabled' => 'boolean',
            'is_archived' => 'boolean',
        ];
    
        public function groups()
        {
            return $this->hasMany(Group::class, 'capstone_stage_id');
        }
}
