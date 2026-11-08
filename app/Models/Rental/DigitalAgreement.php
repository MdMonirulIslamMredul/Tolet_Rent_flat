<?php

namespace App\Models\Rental;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DigitalAgreement extends Model
{
    use HasFactory;

    protected $table = 'digital_agreements';

    protected $fillable = [
        'agreement_no',
        'unit_id',
        'building_id',
        'owner_id',
        'tenant_id',
        'monthly_rent',
        'security_deposit',
        'start_date',
        'end_date',
        'terms_conditions',
        'utility_terms',
        'owner_signature',
        'owner_signed_at',
        'tenant_signature',
        'tenant_signed_at',
        'status', // draft, sent_to_tenant, signed, active, terminated
        'pdf_path',
    ];

    protected $casts = [
        'monthly_rent' => 'decimal:2',
        'security_deposit' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'owner_signed_at' => 'datetime',
        'tenant_signed_at' => 'datetime',
    ];

    public function unit()
    {
        return $this->belongsTo(RentalUnit::class, 'unit_id');
    }

    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }
}

