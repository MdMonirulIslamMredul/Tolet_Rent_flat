<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Rental\Building;
use App\Models\Rental\DigitalAgreement;
use App\Models\Rental\RentalApplication;
use App\Models\Rental\RentalUnit;
use App\Models\Rental\SavedProperty;
use App\Models\Rental\UserVerification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'phone',
        'phone_verified_at',
        'role', // owner, tenant, admin, agency
        'nid_number',
        'nid_front_path',
        'nid_back_path',
        'selfie_path',
        'verification_status', // unverified, pending, verified, rejected
        'verified_at',
        'avatar',
        'address',
        'emergency_contact',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner' || $this->hasRole('owner') || $this->buildings()->exists();
    }

    public function isTenant(): bool
    {
        return $this->role === 'tenant' || $this->hasRole('tenant');
    }

    public function isVerified(): bool
    {
        return $this->verification_status === 'verified';
    }

    public function buildings()
    {
        return $this->hasMany(Building::class, 'owner_id');
    }

    public function units()
    {
        return $this->hasManyThrough(RentalUnit::class, Building::class, 'owner_id', 'building_id');
    }

    public function applicationsAsTenant()
    {
        return $this->hasMany(RentalApplication::class, 'tenant_id');
    }

    public function applicationsAsOwner()
    {
        return $this->hasMany(RentalApplication::class, 'owner_id');
    }

    public function verifications()
    {
        return $this->hasMany(UserVerification::class, 'user_id');
    }

    public function savedProperties()
    {
        return $this->hasMany(SavedProperty::class, 'user_id');
    }

    public function digitalAgreements()
    {
        return $this->hasMany(DigitalAgreement::class, 'tenant_id');
    }
}
