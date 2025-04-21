<?php

namespace App\Http\Controllers\User;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FacebookAd;
use App\Models\WalletTransaction;

class ServiceController extends Controller
{
    public function show(string $id)
    {
        $service = Service::findOrFail($id);

        switch ($service->type) {
            case 'form':
                return view( 'user.services.form', compact('service'));

            default:
                abort(404);
        }
    }
    public function buyFacebookAdService(Request $request)
    {
        $validated = $request->validate([
            'page_link' => 'required|url',
            'budget' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:1',
            'duration' => 'nullable|integer',
            'min_age' => 'nullable|integer',
            'max_age' => 'nullable|integer',
            'location' => 'nullable|string',
            'button' => 'nullable|string',
            'greeting' => 'nullable|string',
        ]);

        $user = auth()->user();

        if ($user->wallet_balance < $request->price) {
            return back()->with('error', 'You do not have enough balance.');
        }

        // Deduct balance
        $user->wallet_balance -= $request->price;
        $user->save();

        // Create wallet transaction
        $transaction = WalletTransaction::create([
            'user_id' => $user->id,
            'amount' => -$request->price,
            'type' => 'payment',
            'method' => 'wallet',
            'transaction_id' => uniqid(),
            'purpose' => 'Facebook Ad Service Purchase',
            'description' => 'Ad for page: ' . $request->page_link,
        ]);

        // Save ad request
        FacebookAd::create([
            'user_id' => $user->id,
            'wallet_transaction_id' => $transaction->id,
            'page_link' => $request->page_link,
            'budget' => $request->budget,
            'duration' => $request->duration,
            'min_age' => $request->min_age,
            'max_age' => $request->max_age,
            'location' => $request->location,
            'button' => $request->button,
            'greeting' => $request->greeting,
            'price' => $request->price,
            'status' => 'approved',
        ]);

        return redirect()->route('customer.dashboard')->with('success', 'Your Facebook ad request has been submitted successfully.');
    }


}
