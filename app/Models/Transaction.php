<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // Tentukan primary key
    protected $primaryKey = 'transaction_id';

    // Tentukan field yang bisa diisi
    protected $fillable = [
        'user_id',
        'category_id',
        'amount',
        'transaction_type',
        'transaction_date',
        'description'
    ];

    // Cast tipe data
    protected $casts = [
        'transaction_date' => 'date',
        'amount' => 'decimal:2'
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Scope untuk pemasukan
    public function scopeIncome($query)
    {
        return $query->where('transaction_type', 'income');
    }

    // Scope untuk pengeluaran
    public function scopeExpense($query)
    {
        return $query->where('transaction_type', 'expense');
    }

    // Scope untuk bulan ini
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('transaction_date', now()->month)
                     ->whereYear('transaction_date', now()->year);
    }

    // Format amount dengan Rp
    public function getFormattedAmountAttribute()
    {
        $prefix = $this->transaction_type == 'income' ? '+' : '-';
        return $prefix . ' Rp ' . number_format($this->amount, 0, ',', '.');
    }
}