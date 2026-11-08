<?php

namespace App\Models\Rental;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVerification extends Model
{
    use HasFactory;

    protected $table = 'user_verifications';

    protected $fillable = [
        'user_id',
        'verification_type',
        'nid_number',
        'nid_front_path',
        'nid_back_path',
        'selfie_path',
        'status', // pending, approved, rejected
        'rejection_reason',
        'admin_note',
        'reviewer_id',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}

