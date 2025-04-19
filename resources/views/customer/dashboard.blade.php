@extends('customer.layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Customer Dashboard</h1>
                    <p>
                        Hello {{ auth()->user()->name }}, 👋🏻 This is your regular dashboard!</p>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard v1</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->


    @php
    $purchasedServiceIds = auth()->user()->orders()->pluck('service_id')->toArray();
    $services = App\Models\Service::whereNotIn('id', $purchasedServiceIds)->get();
    $purchasedServices = App\Models\Service::whereIn('id', $purchasedServiceIds)->get();

    @endphp
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="col-sm-6 my-4">
                <h1 class="text-bold">Your Services</h1>
            </div><!-- /.col -->
            <!-- Small boxes (Stat box) -->
            <div class="row">
                @if ($purchasedServices->isEmpty())
                <div class="col-lg-12">
                    <div class="alert alert-info text-center">
                        <strong>No purchased services found.</strong>
                    </div>
                </div>
                @endif
                @foreach ($purchasedServices as $service)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                        <img src="{{ asset($service->thumbnail) }}" class="card-img-top" alt="{{ $service->title }}" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $service->title }}</h5>
                            <p class="card-text text-muted small">
                                {!! Str::limit(strip_tags($service->description), 100) !!}
                            </p>

                            <div class="d-flex justify-content-between align-items-center">

                                <a href="" class="btn btn-primary btn-sm">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ./col -->
                @endforeach



                <!-- ./col -->
            </div>
            <!-- /.row -->
            <!-- Main row -->
            @if ($services->isNotEmpty())
            <div class="col-sm-6 my-4">
                <h1 class="text-bold">Available Services</h1>
            </div><!-- /.col -->
            <div class="row">
                @foreach ($services as $service)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card card-primary card-outline shadow-sm h-100">
                        <img src="{{ asset( $service->thumbnail) }}" alt="{{ $service->title }}" class="card-img-top" style="height: 200px; object-fit: cover;">

                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">{{ $service->title }}</h5>
                            @if($service->offer_price)
                            <span class="badge bg-success">Offer</span>
                            @else
                            <span class="badge bg-warning">Popular</span>
                            @endif
                        </div>

                        <div class="card-body">


                            <h5 class="text-info">
                                @if($service->offer_price)
                                <del class="text-danger">{{ $service->price }}tk</del>
                                <strong>{{ $service->offer_price }}tk</strong>
                                @else
                                <strong>{{ $service->price }}tk</strong>
                                @endif

                            </h5>
                            <div class="mb-3" style="max-height: 120px; overflow-y: auto;">
                                {!! $service->description !!}
                            </div>



                            <form action="{{ url('/pay') }}" method="POST">
                                @csrf
                                <input type="hidden" name="service_id" value="{{ $service->id }}">
                                <button type="submit" class="btn btn-primary w-100 mt-3">
                                    <i class="fas fa-shopping-cart me-1"></i> Buy Now
                                </button>

                            </form>
                        </div>

                        <div class="card-footer text-muted small d-flex justify-content-between">
                            <span>Category: {{ $service->category->title ?? 'N/A' }}</span>
                            <span>{{ $service->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

@stop
@section('script')

@endsection
