<?php

namespace App\Http\Controllers;

use App\Models\FacebookAd;
use App\Models\ServicePurchase;
use Illuminate\Http\Request;

class ServicePurchaseController extends Controller
{
    public function index()
    {
        // Fetch all service purchases for the authenticated user
        $purchases = ServicePurchase::with('service', 'walletTransaction')->paginate(10);

        return view('admin.service_purchases.index', compact('purchases'));
    }

    public function approve($id)
    {
        $service = ServicePurchase::with('user')->findOrFail($id);
        $service->status = 'approved';
        $service->approved_at = now();
        $service->walletTransaction()->update([
            'status' => 'approved',
        ]);
        $service->user->role = 'customer';
        $service->user->save();
        $service->save();

        return redirect()->route('admin.service.purchases')->with('success', 'Service purchase approved.');
    }

    public function reject($id)
    {
        $service = ServicePurchase::with('user')->findOrFail($id);
        $service->status = 'rejected';
        $service->user->wallet_balance += $service->price;
        $service->user->save();
        $service->walletTransaction()->update([
            'status' => 'rejected',
        ]);
        $service->save();

        return redirect()->route('admin.service.purchases')->with('error', 'Service purchase rejected.');
    }

    public function facebookAdRequests()
    {
        // Fetch all service purchases for the authenticated user
        $facebookAdRequests = FacebookAd::where('status', 'approved')->with('walletTransaction', 'user')->paginate(10);
        return view('admin.facebook_ad_requests.index', compact('facebookAdRequests'));
    }



}
