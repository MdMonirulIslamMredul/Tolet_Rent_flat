<?php

namespace App\Models\Rental;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedProperty extends Model
{
    use HasFactory;

    protected $table = 'saved_properties';

    protected $fillable = [
        'user_id',
        'building_id',
        'unit_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    public function unit()
    {
        return $this->belongsTo(RentalUnit::class, 'unit_id');
    }
}

