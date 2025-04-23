@extends('customer.layouts.app')

@section('content')

<div class="content-wrapper">
    <!-- Page Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Service Dashboard</h1>
                    <p class="mt-2">Hello <strong>{{ auth()->user()->name }}</strong>, 👋🏻 Here are your Facebook posts and their insights.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right bg-light p-2 rounded">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Facebook Insights</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @foreach($posts as $index => $post)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow-sm border-primary">
                            <div class="card-header bg-primary text-white">
                                Post #{{ $index + 1 }}
                            </div>
                            <div class="card-body">
                                <p><strong>ID:</strong> {{ $post['id'] }}</p>
                                <p><strong>Message:</strong> {{ $post['message'] ?? 'No message' }}</p>
                                <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($post['created_time'])->format('M d, Y h:i A') }}</p>

                                @php
                                    $insight = $insights[$index] ?? null;
                                    $data = $insight['data'][0] ?? null;
                                    $value = $data['values'][0]['value'] ?? 'N/A';
                                @endphp

                                @if($data)
                                    <hr>
                                    <p class="mb-0"><strong>{{ $data['name'] }}:</strong> {{ is_array($value) ? json_encode($value) : $value }}</p>
                                @else
                                    <p class="text-muted">No insight data.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</div>

@stop
