<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // Menampilkan semua transaksi
    public function index(Request $request)
    {
        $userId = Auth::id();
        
        // Query dasar
        $query = Transaction::where('user_id', $userId)
            ->with('category')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc');
        
        // Filter berdasarkan jenis transaksi
        if ($request->has('type') && $request->type != '') {
            $query->where('transaction_type', $request->type);
        }
        
        // Filter berdasarkan kategori
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }
        
        // Filter berdasarkan tanggal
        if ($request->has('start_date') && $request->start_date != '') {
            $query->where('transaction_date', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date != '') {
            $query->where('transaction_date', '<=', $request->end_date);
        }
        
        // Filter pencarian deskripsi
        if ($request->has('search') && $request->search != '') {
            $query->where('description', 'like', '%' . $request->search . '%');
        }
        
        $transactions = $query->paginate(10);
        
        // Statistik
        $totalTransactions = Transaction::where('user_id', $userId)->count();
        $totalIncome = Transaction::where('user_id', $userId)
            ->where('transaction_type', 'income')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');
        $totalExpense = Transaction::where('user_id', $userId)
            ->where('transaction_type', 'expense')
            ->whereMonth('transaction_date', now()->month)
            ->whereYear('transaction_date', now()->year)
            ->sum('amount');
        
        // Get categories for filter dropdown
        $categories = Category::where('user_id', $userId)->get();
        
        return view('transactions', compact(
            'transactions',
            'totalTransactions',
            'totalIncome',
            'totalExpense',
            'categories'
        ));
    }

    // API untuk mengambil data transaksi (untuk edit modal)
    public function edit($id)
    {
        $transaction = Transaction::findOrFail($id);
        
        // Cek ownership
        if ($transaction->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        return response()->json([
            'transaction_id' => $transaction->transaction_id,
            'transaction_date' => $transaction->transaction_date->format('Y-m-d'),
            'description' => $transaction->description,
            'category_id' => $transaction->category_id,
            'amount' => $transaction->amount,
            'transaction_type' => $transaction->transaction_type
        ]);
    }

    // Menyimpan transaksi baru (dari modal)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,category_id',
            'amount' => 'required|numeric|min:0',
            'transaction_type' => 'required|in:income,expense',
            'transaction_date' => 'required|date',
            'description' => 'required|string|max:255'
        ]);

        $validated['user_id'] = Auth::id();
        
        Transaction::create($validated);
        
        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil ditambahkan!');
    }

    // Update transaksi (dari modal)
    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        
        // Cek ownership
        if ($transaction->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,category_id',
            'amount' => 'required|numeric|min:0',
            'transaction_type' => 'required|in:income,expense',
            'transaction_date' => 'required|date',
            'description' => 'required|string|max:255'
        ]);
        
        $transaction->update($validated);
        
        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil diperbarui!');
    }

    // Hapus transaksi
    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        
        // Cek ownership
        if ($transaction->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }
        
        $transaction->delete();
        
        return redirect()->route('transactions.index')
            ->with('success', 'Transaksi berhasil dihapus!');
    }
}