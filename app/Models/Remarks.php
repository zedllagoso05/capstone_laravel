<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Remarks extends Model
{
    protected $table = 'remark_evaluations';

    protected $fillable = [
    'group_id',
    'milestone_id',
    'adviser_id',
    'all_present',
    'compiled',
    'deduction_points',
    'feedback',
    'remarks',
    'date_evaluated',
];

    protected $casts = [
        'all_present' => 'boolean',
        'compiled'    => 'boolean',
        'date_evaluated' => 'datetime',
    ];
}