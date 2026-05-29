<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstitutionProgramSubject extends Model
{
    protected $fillable = [
        'institution_program_id',
        'subject_name',
        'is_optional',
    ];

    protected function casts(): array
    {
        return [
            'is_optional' => 'boolean',
        ];
    }

    public function institutionProgram(): BelongsTo
    {
        return $this->belongsTo(InstitutionProgram::class);
    }
}
