<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Tentukan primary key
    protected $primaryKey = 'category_id';

    // Tentukan field yang bisa diisi
    protected $fillable = [
        'user_id',
        'category_name',
        'type'
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Transaction
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'category_id');
    }

    // Scope untuk pemasukan
    public function scopeIncome($query)
    {
        return $query->where('type', 'income');
    }

    // Scope untuk pengeluaran
    public function scopeExpense($query)
    {
        return $query->where('type', 'expense');
    }
}