<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class InstitutionUser extends Pivot
{
    protected $table = 'institution_user';

    public $incrementing = true;

    protected $casts = [
        'is_primary' => 'boolean',
        'is_active'  => 'boolean',
        'joined_at'  => 'datetime',
    ];
}
