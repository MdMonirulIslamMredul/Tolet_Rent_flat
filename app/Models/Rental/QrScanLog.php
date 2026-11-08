<?php

namespace App\Models\Rental;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QrScanLog extends Model
{
    use HasFactory;

    protected $table = 'qr_scans_log';

    protected $fillable = [
        'building_id',
        'user_id',
        'ip_address',
        'user_agent',
        'referrer',
        'scanned_at',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

