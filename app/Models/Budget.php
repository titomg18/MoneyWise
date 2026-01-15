<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_name',
        'amount',
        'spent_amount',
        'period',
        'color',
        'description',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'spent_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Transaction (BARU)
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'budget_id');
    }

    // Auto update spent_amount saat ada perubahan transaksi
    protected static function booted()
    {
        static::updated(function ($budget) {
            // Hitung ulang spent_amount dari transaksi yang terkait
            $budget->updateSpentAmount();
        });
    }

    // Method untuk update spent_amount
    public function updateSpentAmount()
    {
        $this->spent_amount = $this->transactions()
            ->where('transaction_type', 'expense')
            ->sum('amount');
        $this->saveQuietly(); // save tanpa trigger event
    }

    public function getRemainingAttribute()
    {
        return $this->amount - $this->spent_amount;
    }

    public function getPercentageAttribute()
    {
        if ($this->amount == 0) return 0;
        return min(100, ($this->spent_amount / $this->amount) * 100);
    }
}