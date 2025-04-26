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
                <div class="col">
                    <a href="https://digitalwaveit.com/e-commerce/">
                        <div class="card text-center service-card p-3">
                            <div class="rounded-icon text-primary mb-2">🖥️</div>
                            <div>ওয়েব ডিজাইন</div>
                        </div>
                    </a>
                </div>
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
            <!-- Main row -->
            <div class="row">


            </div>
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>

@stop
