<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
      protected $fillable = [
        'certificate_title',
        'certificate_description',
        'milestone_id',
    ];

    public function milestone()
    {
        return $this->belongsTo(Milestone::class);
    }

    public function groupcertificate()
    {
        return $this->belongsTo(GroupCertificate::class);
    }
}
