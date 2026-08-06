<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupCertificate extends Model
{
    protected $fillable = [
        'group_id',
        'certificate_id',
        'issued_date',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

public function certificate()
{
    return $this->belongsTo(Certificate::class, 'certificate_id');
}
}
