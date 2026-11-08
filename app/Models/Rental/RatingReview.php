<?php

namespace App\Models\Rental;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingReview extends Model
{
    use HasFactory;

    protected $table = 'ratings_reviews';

    protected $fillable = [
        'reviewer_id',
        'reviewee_id',
        'building_id',
        'unit_id',
        'review_type',
        'rating',
        'comment',
        'is_verified_stay',
        'status',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_verified_stay' => 'boolean',
    ];

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function reviewee()
    {
        return $this->belongsTo(User::class, 'reviewee_id');
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

