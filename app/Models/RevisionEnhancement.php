<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RevisionEnhancement extends Model
{
    protected $table = 'revisions_enhancements';

    protected $fillable = ['revision_id', 'enhancement', 'remarks'];

    public function revision()
    {
        return $this->belongsTo(Revision::class);
    }
}