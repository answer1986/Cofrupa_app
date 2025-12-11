<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index()
    {
        // Get overdue payments
        $overduePayments = Purchase::with('supplier')
            ->whereNotNull('payment_due_date')
            ->where('payment_due_date', '<', now())
            ->where('amount_owed', '>', 0)
            ->orderBy('payment_due_date')
            ->get();

        // Get upcoming payments (next 7 days)
        $upcomingPayments = Purchase::with('supplier')
            ->whereNotNull('payment_due_date')
            ->where('payment_due_date', '>=', now())
            ->where('payment_due_date', '<=', now()->addDays(7))
            ->where('amount_owed', '>', 0)
            ->orderBy('payment_due_date')
            ->get();

        // Get suppliers with outstanding debt
        $suppliersWithDebt = Supplier::where('total_debt', '>', 0)
            ->orderBy('total_debt', 'desc')
            ->get();

        // Calculate totals
        $totalOutstanding = Purchase::where('amount_owed', '>', 0)->sum('amount_owed');
        $totalOverdue = $overduePayments->sum('amount_owed');
        $totalDueThisWeek = $upcomingPayments->sum('amount_owed');

        return view('reports.index', compact(
            'overduePayments',
            'upcomingPayments',
            'suppliersWithDebt',
            'totalOutstanding',
            'totalOverdue',
            'totalDueThisWeek'
        ));
    }

    public function payments(Request $request)
    {
        $query = Purchase::with('supplier')
            ->where('amount_owed', '>', 0);

        // Apply filters
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'overdue':
                    $query->whereNotNull('payment_due_date')
                          ->where('payment_due_date', '<', now());
                    break;
                case 'urgent':
                    $query->whereNotNull('payment_due_date')
                          ->where('payment_due_date', '>=', now())
                          ->where('payment_due_date', '<=', now()->addDays(3));
                    break;
                case 'warning':
                    $query->whereNotNull('payment_due_date')
                          ->where('payment_due_date', '>', now()->addDays(3))
                          ->where('payment_due_date', '<=', now()->addDays(7));
                    break;
                case 'normal':
                    $query->where(function($q) {
                        $q->whereNull('payment_due_date')
                          ->orWhere('payment_due_date', '>', now()->addDays(7));
                    });
                    break;
            }
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('min_amount')) {
            $query->where('amount_owed', '>=', $request->min_amount);
        }

        if ($request->filled('max_amount')) {
            $query->where('amount_owed', '<=', $request->max_amount);
        }

        $purchases = $query->orderBy('payment_due_date')
                          ->paginate(20)
                          ->appends($request->query());

        return view('reports.payments', compact('purchases'));
    }

    public function supplierDebts()
    {
        $suppliers = Supplier::with(['purchases' => function($query) {
            $query->where('amount_owed', '>', 0)->orderBy('payment_due_date');
        }])
        ->where('total_debt', '>', 0)
        ->orderBy('total_debt', 'desc')
        ->paginate(15);

        return view('reports.supplier-debts', compact('suppliers'));
    }
}
