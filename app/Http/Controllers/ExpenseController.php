<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        // Vaqt o'zgarib ketmasligi uchun Laravelda formatlab olamiz
        $expenses = Expense::latest()->get()->map(function ($expense) {
            $expense->formatted_expense_date = $expense->expense_date->format('Y-m-d\TH:i');
            return $expense;
        });

        // Jami xarajatlar summasini hisoblash
        $total_expenses = Expense::sum('amount');

        return view('expenses.index', compact('expenses', 'total_expenses'));
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'amount' => ['required', 'numeric', 'min:0'],
            'expense_date' => ['required', 'date'],
        ]);

        Expense::create($fields);

        return redirect()->route('expenses.index')->with('success', 'Xarajat muvaffaqiyatli qo‘shildi!');
    }

    public function update(Request $request, Expense $expense)
    {
        $fields = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'amount' => ['required', 'numeric', 'min:0'],
            'expense_date' => ['required', 'date'],
        ]);

        $expense->update($fields);

        return redirect()->route('expenses.index')->with('success', 'Xarajat ma’lumotlari yangilandi!');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Xarajat o‘chirib tashlandi!');
    }
}
