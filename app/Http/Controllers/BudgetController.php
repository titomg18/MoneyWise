<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $budgets = Budget::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        $totalBudget = $budgets->sum('amount');
        $totalSpent = $budgets->sum('spent_amount');
        $remainingBudget = $totalBudget - $totalSpent;
        $totalSpentPercentage = $totalBudget > 0 ? ($totalSpent / $totalBudget) * 100 : 0;
        
        return view('anggaran', [
            'budgets' => $budgets,
            'totalBudget' => $totalBudget,
            'totalSpent' => $totalSpent,
            'remainingBudget' => $remainingBudget,
            'totalSpentPercentage' => $totalSpentPercentage
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('budget.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'period' => 'required|in:Bulanan,Mingguan,Tahunan,Custom',
            'color' => 'nullable|string',
            'description' => 'nullable|string'
        ]);

        $budget = new Budget();
        $budget->user_id = Auth::id();
        $budget->category_name = $request->category_name;
        $budget->amount = $request->amount;
        $budget->period = $request->period;
        $budget->color = $request->color ?? '#EF4444'; // Default warna merah
        $budget->description = $request->description;
        
        if ($request->period === 'Custom' && $request->start_date && $request->end_date) {
            $budget->start_date = $request->start_date;
            $budget->end_date = $request->end_date;
        }

        $budget->save();

        return response()->json([
            'success' => true,
            'message' => 'Anggaran berhasil ditambahkan!',
            'budget' => $budget
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Budget $budget)
    {
        // Pastikan user hanya bisa mengedit anggaran miliknya sendiri
        if ($budget->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        return response()->json($budget);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Budget $budget)
    {
        // Pastikan user hanya bisa mengupdate anggaran miliknya sendiri
        if ($budget->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'category_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'period' => 'required|in:Bulanan,Mingguan,Tahunan,Custom',
            'color' => 'nullable|string',
            'description' => 'nullable|string'
        ]);

        $budget->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Anggaran berhasil diperbarui!',
            'budget' => $budget
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Budget $budget)
    {
        // Pastikan user hanya bisa menghapus anggaran miliknya sendiri
        if ($budget->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $budget->delete();

        return response()->json([
            'success' => true,
            'message' => 'Anggaran berhasil dihapus!'
        ]);
    }

    /**
     * Get budget data in JSON format for AJAX requests
     */
    public function getBudgetData()
    {
        $budgets = Budget::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        $totalBudget = $budgets->sum('amount');
        $totalSpent = $budgets->sum('spent_amount');
        $remainingBudget = $totalBudget - $totalSpent;
        
        // Format data untuk response JSON
        $formattedBudgets = $budgets->map(function($budget) {
            return [
                'id' => $budget->id,
                'category_name' => $budget->category_name,
                'amount' => (float) $budget->amount,
                'spent_amount' => (float) $budget->spent_amount,
                'period' => $budget->period,
                'color' => $budget->color,
                'description' => $budget->description,
                'remaining' => (float) ($budget->amount - $budget->spent_amount),
                'percentage' => $budget->amount > 0 ? round(($budget->spent_amount / $budget->amount) * 100) : 0
            ];
        });
        
        return response()->json([
            'budgets' => $formattedBudgets,
            'totalBudget' => (float) $totalBudget,
            'totalSpent' => (float) $totalSpent,
            'remainingBudget' => (float) $remainingBudget,
            'totalSpentPercentage' => $totalBudget > 0 ? round(($totalSpent / $totalBudget) * 100) : 0
        ]);
    }
}