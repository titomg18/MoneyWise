<?php

namespace App\Http\Controllers;

use App\Models\Savings;
use Illuminate\Http\Request;

class SavingsController extends Controller
{
    public function index()
    {
        $savings = Savings::where('user_id', auth()->id())->get();
        
        $activeSavings = $savings->where('status', 'active')->count();
        $completedSavings = $savings->where('status', 'completed')->count();
        $totalTarget = $savings->sum('target_amount');
        $totalSaved = $savings->sum('saved_amount');

        return view('tabungan', [
            'savings' => $savings,
            'activeSavings' => $activeSavings,
            'completedSavings' => $completedSavings,
            'totalTarget' => $totalTarget,
            'totalSaved' => $totalSaved,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'target_name' => 'required|string|max:255',
            'target_amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'target_category' => 'nullable|string',
            'icon' => 'nullable|string',
            'color' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['saved_amount'] = 0;
        $validated['status'] = 'active';

        Savings::create($validated);

        return redirect()->back()->with('success', 'Target tabungan berhasil dibuat!');
    }

    public function update(Request $request, Savings $savings)
    {
        // Pastikan user hanya bisa mengupdate data miliknya
        if ($savings->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'saved_amount' => 'required|numeric|min:0',
            'status' => 'nullable|in:active,completed,cancelled',
            'target_name' => 'nullable|string|max:255',
            'target_amount' => 'nullable|numeric|min:0',
            'end_date' => 'nullable|date',
        ]);

        // Cek apakah target sudah tercapai
        if (isset($validated['saved_amount']) && $validated['saved_amount'] >= $savings->target_amount) {
            $validated['status'] = 'completed';
        }

        $savings->update($validated);

        return redirect()->back()->with('success', 'Target tabungan berhasil diperbarui!');
    }

    public function destroy(Savings $savings)
    {
        // Pastikan user hanya bisa menghapus data miliknya
        if ($savings->user_id !== auth()->id()) {
            abort(403);
        }

        $savings->delete();

        return redirect()->back()->with('success', 'Target tabungan berhasil dihapus!');
    }

    public function addSavings(Request $request, Savings $savings)
    {
        // Pastikan user hanya bisa menambah tabungan miliknya
        if ($savings->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $newAmount = $savings->saved_amount + $validated['amount'];
        
        // Update status jika target tercapai
        if ($newAmount >= $savings->target_amount) {
            $savings->update([
                'saved_amount' => $newAmount,
                'status' => 'completed',
            ]);
        } else {
            $savings->update(['saved_amount' => $newAmount]);
        }

        return redirect()->back()->with('success', 'Tabungan berhasil ditambahkan!');
    }
}
