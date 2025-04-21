@extends('user.layouts.app')

@section("css")
<style>
        body {
            font-family: "Helvetica Neue", sans-serif;
            background-color: #f9f9f9;
        }
        .service-card {
            transition: all 0.3s ease;
        }
        .service-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .rounded-icon {
            font-size: 32px;
        }
    </style>

@endsection

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <div class="d-flex justify-content-between">
                        <h1 class="m-0 text-dark">User Dashboard</h1>

                    </div>


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
            <!-- Welcome -->
            <div class="text-center mb-4">
                <h4>স্বাগতম, {{ auth()->user()->name }}</h4>
                <button class="btn btn-primary mt-2">একটি সার্ভিস বুকিং করুন</button>
            </div>

            <!-- Services -->
            <h5 class="mb-3">আমাদের সার্ভিসসমূহ</h5>
            <div class="row row-cols-2 row-cols-md-4 g-3 mb-4">
                @if (!empty($services))
                    @foreach ($services as $service)
                        <div class="col">
                            <a href="{{ route('user.services.show', $service->id) }}">
                            <div class="card text-center service-card p-3">
                                <div class="rounded-icon text-primary mb-2">{{ $service->icon }}</div>
                                <div>{{ $service->title }}</div>
                            </div>
                            </a>
                        </div>
                    @endforeach
                @endif
                <!-- <div class="col">
                    <div class="card text-center service-card p-3">
                        <div class="rounded-icon text-primary mb-2">🎬</div>
                        <div>ভিডিও মার্কেটিং</div>
                    </div>
                </div> -->
                <!-- <div class="col">
                    <div class="card text-center service-card p-3">
                        <div class="rounded-icon text-primary mb-2">📢</div>
                        <div>ফেসবুক অ্যাডস</div>
                    </div>
                </div>
                <div class="col">
                    <div class="card text-center service-card p-3">
                        <div class="rounded-icon text-primary mb-2">🖥️</div>
                        <div>ওয়েব ডিজাইন</div>
                    </div>
                </div>
                <div class="col">
                    <div class="card text-center service-card p-3">
                        <div class="rounded-icon text-primary mb-2">⚙️</div>
                        <div>অন্যান্য</div>
                    </div>
                </div> -->
            </div>


            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>150</h3>

                            <p>New Orders</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>53<sup style="font-size: 20px">%</sup></h3>

                            <p>Bounce Rate</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>44</h3>

                            <p>User Registrations</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-6">
                    <!-- small box -->
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>65</h3>

                            <p>Unique Visitors</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
            <!-- Main row -->
            <div class="row">


            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

@stop
