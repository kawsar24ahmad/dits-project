<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\WalletTransaction;
use App\Http\Controllers\Controller;

class WalletTransactionController extends Controller
{
    public function index()
    {
        $transactions = WalletTransaction::latest()->get();
        return view('admin.wallet.index', compact('transactions'));
    }

    public function update(Request $request, WalletTransaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        if ($request->status == 'approved' && $transaction->status != 'approved') {
            $transaction->user->increment('wallet_balance', $transaction->amount);
        }

        $transaction->update(['status' => $request->status]);

        return back()->with('success', 'Transaction updated successfully.');
    }

}
