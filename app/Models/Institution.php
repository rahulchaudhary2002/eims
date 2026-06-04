<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Institution extends Model
{
    use HasFactory, SoftDeletes, HasSlug;

    const TYPES = [
        'university'       => 'University',
        'college'          => 'College',
        'school'           => 'School',
        'consultancy'      => 'Consultancy',
        'institute'        => 'Institute',
        'training_center'  => 'Training Center',
        'other'            => 'Other',
    ];

    const STATUSES = [
        'active'    => 'Active',
        'inactive'  => 'Inactive',
        'pending'   => 'Pending',
        'suspended' => 'Suspended',
    ];

    protected $fillable = [
        'parent_id',
        'type',
        'name',
        'slug',
        'code',
        'email',
        'phone',
        'website',
        'logo',
        'cover_image',
        'short_description',
        'description',
        'established_year',
        'country',
        'province',
        'district',
        'city',
        'address',
        'latitude',
        'longitude',
        'is_verified',
        'verified_at',
        'status',
        'is_featured',
        'sort_order',
    ];

    protected $casts = [
        'latitude'     => 'decimal:8',
        'longitude'    => 'decimal:8',
        'is_verified'  => 'boolean',
        'verified_at'  => 'datetime',
        'is_featured'  => 'boolean',
        'sort_order'   => 'integer',
        'established_year' => 'integer',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_institutions')
            ->withPivot(['role', 'is_primary', 'is_active', 'joined_at'])
            ->withTimestamps();
    }

    public function userInstitutions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserInstitution::class);
    }

    public function activeUsers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->users()->wherePivot('is_active', true);
    }

    public function profile(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(\App\Models\InstitutionProfile::class);
    }

    public function documents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\InstitutionDocument::class);
    }

    public function programs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\InstitutionProgram::class);
    }

    public function scholarships(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Scholarship::class);
    }

    public function applications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Application::class);
    }

    public function referralAgreements(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\ReferralAgreement::class);
    }

    public function referrals(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Referral::class);
    }

    public function commissionInvoices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\CommissionInvoice::class);
    }

    public function inquiries(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Inquiry::class);
    }

    public function posts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Post::class);
    }

    public function followers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\InstitutionFollower::class);
    }

    public function consultancyDestinations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\ConsultancyDestination::class);
    }

    public function consultancyServices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\ConsultancyService::class);
    }

    public function counselingSessions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\CounselingSession::class);
    }

    public function subscriptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\InstitutionSubscription::class);
    }

    public function promotions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Promotion::class);
    }

    public function reviews(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\InstitutionReview::class);
    }

    public function conversations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Conversation::class);
    }

    public function rewardClaims(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\StudentRewardClaim::class);
    }
}
