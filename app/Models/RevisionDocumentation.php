<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevisionDocumentation extends Model
{
    use HasFactory;
    protected $table = 'revisions_documentation';

    protected $fillable = [
        'revision_id',
        'chapter',
        'findings',
        'remarks',
    ];

    /**
     * Get the revision this documentation belongs to.
     */
    public function revision()
    {
        return $this->belongsTo(Revision::class);
    }
}