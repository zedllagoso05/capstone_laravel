<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RevisionObjective extends Model
{
    protected $table = 'revisions_objectives';

    protected $fillable = ['revision_id', 'objective', 'remarks'];

    public function revision()
    {
        return $this->belongsTo(Revision::class);
    }
}