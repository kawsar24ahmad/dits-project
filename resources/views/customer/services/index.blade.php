@extends('customer.layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Your Services</h1>

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
            <!-- Small boxes (Stat box) -->
            @if ($purchasedServices->isEmpty())
            <div class="col-lg-12">
                <div class="alert alert-info text-center">
                    <strong>No purchased services found.</strong>
                </div>
            </div>
            @endif
            <div class="row">
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

        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

@stop
@section('script')

@endsection
