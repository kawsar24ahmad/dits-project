<?php

namespace App\Http\Controllers\Customer;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServiceController extends Controller
{
    public function index()
    {
        $purchasedServiceIds = auth()->user()->orders()->whereIn('status', ['Completed', 'Processing'])
            ->pluck('service_id')->toArray();
        $purchasedServices = Service::whereIn('id', $purchasedServiceIds)
                ->get();


        return view('customer.services.index', compact('purchasedServices'));
    }

    public function showAll()
    {
        $purchasedServiceIds = auth()->user()->orders()->whereIn('status', ['Completed', 'Processing'])
            ->pluck('service_id')->toArray();
        $services = Service::whereNot('id', $purchasedServiceIds)
            ->get();

        return view('customer.services.all', compact('services'));
    }
}
