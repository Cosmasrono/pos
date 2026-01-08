<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with(['category', 'recordedBy', 'approvedBy'])
            ->latest()
            ->paginate(15);
        
        return view('finance.expenses.index', compact('expenses'));
    }

    public function create()
    {
        $categories = ExpenseCategory::all();
        return view('finance.expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => ['required', 'exists:expense_categories,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'description' => ['required', 'string'],
            'expense_date' => ['required', 'date'],
            'payment_method' => ['required', 'in:cash,bank_transfer,cheque,mpesa'],
            'reference_number' => ['nullable', 'string', 'max:255'],
        ]);

        Expense::create([
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'description' => $request->description,
            'expense_date' => $request->expense_date,
            'payment_method' => $request->payment_method,
            'reference_number' => $request->reference_number,
            'recorded_by' => auth()->id(),
            'status' => 'pending',
        ]);

        return redirect()->route('expenses.index')->with('success', 'Expense recorded successfully.');
    }

    public function approve(Expense $expense)
    {
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->isManager()) {
            abort(403, 'Unauthorized action.');
        }

        $expense->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('expenses.index')->with('success', 'Expense approved successfully.');
    }

    public function reject(Request $request, Expense $expense)
    {
        if (!auth()->user()->isSuperAdmin() && !auth()->user()->isManager()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'rejection_reason' => ['required', 'string'],
        ]);

        $expense->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()->route('expenses.index')->with('success', 'Expense rejected.');
    }

    public function destroy(Expense $expense)
    {
        if ($expense->isApproved()) {
            return back()->withErrors(['error' => 'Cannot delete approved expenses.']);
        }

        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}
