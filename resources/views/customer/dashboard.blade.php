@extends('customer.layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                <div class="d-flex justify-content-between">
                        <h1 class="m-0 text-dark">Service Dashboard</h1>
                    </div>
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



    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Main row -->
            <div class="row">
                <h5 class="mb-3">Your Services</h5>
                <div class="row row-cols-2 row-cols-md-4 g-3 mb-4">

                    @php
                    $services = App\Models\ServicePurchase::with('service')->where([
                        'user_id'=> auth()->id(),
                        'status' => 'approved'
                    ])->get();
                    @endphp
                    @foreach ($services as $service)
                        <div class="col">
                            <div class="card text-center service-card p-3">
                                <div class="rounded-icon text-primary mb-2">📢</div>
                                <div>{{ $service->service->title }}</div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

@stop
