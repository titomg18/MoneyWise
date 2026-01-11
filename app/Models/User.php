<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ===== RELASI BARU =====
    // Relasi ke Category
    public function categories()
    {
        return $this->hasMany(Category::class, 'user_id');
    }

    // Relasi ke Transaction
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    // Get total saldo
    public function getBalanceAttribute()
    {
        $totalIncome = $this->transactions()->income()->sum('amount');
        $totalExpense = $this->transactions()->expense()->sum('amount');
        return $totalIncome - $totalExpense;
    }

    // Get total pemasukan bulan ini
    public function getMonthlyIncomeAttribute()
    {
        return $this->transactions()
                    ->income()
                    ->thisMonth()
                    ->sum('amount');
    }

    // Get total pengeluaran bulan ini
    public function getMonthlyExpenseAttribute()
    {
        return $this->transactions()
                    ->expense()
                    ->thisMonth()
                    ->sum('amount');
    }
}