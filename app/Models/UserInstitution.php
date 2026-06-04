<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserInstitution extends Model
{
    use SoftDeletes;

    protected $table = 'user_institutions';

    public const ROLES = [
        'owner'           => 'Owner',
        'admin'           => 'Admin',
        'manager'         => 'Manager',
        'admission_officer' => 'Admission Officer',
        'counselor'       => 'Counselor',
        'content_manager' => 'Content Manager',
        'finance_officer' => 'Finance Officer',
        'staff'           => 'Staff',
    ];

    protected $fillable = [
        'user_id',
        'institution_id',
        'role',
        'is_primary',
        'is_active',
        'joined_at',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'is_active'  => 'boolean',
            'joined_at'  => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}
