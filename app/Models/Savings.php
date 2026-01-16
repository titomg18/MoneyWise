<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Savings extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'target_name',
        'target_amount',
        'saved_amount',
        'start_date',
        'end_date',
        'target_category',
        'icon',
        'color',
        'status',
        'description',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'target_amount' => 'decimal:2',
        'saved_amount' => 'decimal:2',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Hitung progress tabungan
    public function getProgressPercentageAttribute()
    {
        if ($this->target_amount == 0) {
            return 0;
        }
        return round(($this->saved_amount / $this->target_amount) * 100, 2);
    }

    // Hitung sisa target
    public function getRemainingAmountAttribute()
    {
        return max(0, $this->target_amount - $this->saved_amount);
    }

    // Hitung hari tersisa
    public function getDaysRemainingAttribute()
    {
        if (!$this->end_date || $this->status !== 'active') {
            return null;
        }
        return now()->diffInDays($this->end_date, false);
    }

    // Cek apakah target sudah tercapai
    public function getIsCompletedAttribute()
    {
        return $this->saved_amount >= $this->target_amount;
    }
}
