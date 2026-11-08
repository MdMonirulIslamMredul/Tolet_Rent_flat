<?php

namespace App\Models\Rental;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalApplication extends Model
{
    use HasFactory;

    protected $table = 'rental_applications';

    protected $fillable = [
        'application_no',
        'unit_id',
        'building_id',
        'tenant_id',
        'owner_id',
        'status', // pending, viewed, shortlisted, approved, rejected, cancelled
        'proposed_move_in_date',
        'family_members_count',
        'profession',
        'monthly_income_range',
        'current_address',
        'message_to_owner',
        'owner_notes',
        'rejection_reason',
    ];

    protected $casts = [
        'proposed_move_in_date' => 'date',
        'family_members_count' => 'integer',
    ];

    public function unit()
    {
        return $this->belongsTo(RentalUnit::class, 'unit_id');
    }

    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'approved' => ['class' => 'bg-success', 'text' => 'অনুমোদিত (Approved)'],
            'shortlisted' => ['class' => 'bg-info', 'text' => 'বাছাইকৃত (Shortlisted)'],
            'rejected' => ['class' => 'bg-danger', 'text' => 'বাতিল (Rejected)'],
            'viewed' => ['class' => 'bg-secondary', 'text' => 'দেখা হয়েছে (Viewed)'],
            default => ['class' => 'bg-warning text-dark', 'text' => 'বিচারাধীন (Pending)'],
        };
    }
}

