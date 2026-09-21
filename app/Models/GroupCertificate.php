<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupCertificate extends Model
{
    protected $table = 'group_certificates';

    protected $fillable = [
        'group_id',
        'certificate_id',
        'serial_number',
        'issued_date',
    ];

    protected $casts = [
        'issued_date' => 'date',
    ];

    /**
     * Every time a GroupCertificate row is created anywhere in the app
     * (teacher issuance, milestone auto-completion, seeders, etc.),
     * Laravel will fire this hook and assign a serial number.
     */
    protected static function booted(): void
    {
        static::creating(function (self $model) {
            if (empty($model->serial_number)) {
                $docType = $model->certificate?->document_type
                    ?? Certificate::find($model->certificate_id)?->document_type;

                $model->serial_number = static::generateSerialNumber($docType);
            }
        });
    }

    /**
     * Format: MCC-{TYPE}-{YEAR}-{SEQ}
     * Examples:
     *   MCC-REC-2026-0001   (Recommendation Sheet)
     *   MCC-APR-2026-0007   (Approval Sheet)
     *   MCC-CERT-2026-0003  (Capstone 1 Certificate)
     *   MCC-DOC-2026-0011   (any other document)
     */
    public static function generateSerialNumber(?string $documentType = null): string
    {
        $year = now()->year;

        $typeCode = match (strtolower((string) $documentType)) {
            'recommendation' => 'REC',
            'approval'       => 'APR',
            'certificate'    => 'CERT',
            default          => 'DOC',
        };

        $prefix = "MCC-{$typeCode}-{$year}-";

        // Highest sequence already used for this prefix
        $last = static::query()
            ->where('serial_number', 'like', $prefix . '%')
            ->orderByDesc('serial_number')
            ->value('serial_number');

        $nextSeq = $last
            ? ((int) substr($last, strlen($prefix))) + 1
            : 1;

        // Collision-safe (in case rows get deleted / re-created)
        do {
            $candidate = $prefix . str_pad($nextSeq, 4, '0', STR_PAD_LEFT);
            if (! static::where('serial_number', $candidate)->exists()) {
                break;
            }
            $nextSeq++;
        } while (true);

        return $candidate;
    }

    public function certificate()
    {
        return $this->belongsTo(Certificate::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}