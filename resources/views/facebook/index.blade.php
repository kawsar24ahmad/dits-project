@extends('customer.layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Page Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-4 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">📊 Facebook Insights</h1>
                    <p class="mt-2">Welcome <strong>{{ auth()->user()->name }}</strong> 👋🏻 — here are the latest stats from your Facebook posts.</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right bg-light p-2 rounded shadow-sm">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Insights</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                @forelse($results as $index => $post)
                    <div class="col-md-6 mb-4">
                        <div class="card shadow border-0 h-100">
                            <div class="card-header bg-primary text-white d-flex justify-content-between">
                                <span><i class="fas fa-feather-alt me-1"></i> Post #{{ $index + 1 }}</span>
                                <small>{{ \Carbon\Carbon::parse($post['created_time'])->format('M d, Y h:i A') }}</small>
                            </div>
                            <div class="card-body">
                                <p class="mb-2"><strong>Message:</strong> <br>{{ $post['message'] ?? '— No message —' }}</p>

                                <hr class="my-2">

                                <div class="row text-center">
                                    <div class="col">
                                        <span class="badge bg-success mb-1"><i class="fas fa-thumbs-up"></i> Likes</span>
                                        <h5>{{ $post['likes'] }}</h5>
                                    </div>
                                    <div class="col">
                                        <span class="badge bg-info mb-1"><i class="fas fa-comments"></i> Comments</span>
                                        <h5>{{ $post['comments'] }}</h5>
                                    </div>
                                    <div class="col">
                                        <span class="badge bg-warning mb-1"><i class="fas fa-share"></i> Shares</span>
                                        <h5>{{ $post['shares'] }}</h5>
                                    </div>
                                </div>

                                <hr class="my-2">

                                <div class="row text-center">
                                    <div class="col">
                                        <span class="badge bg-secondary mb-1"><i class="fas fa-eye"></i> Reach</span>
                                        <h5>{{ $post['reach'] ?? 0 }}</h5>
                                    </div>
                                    <div class="col">
                                        <span class="badge bg-dark mb-1"><i class="fas fa-user-check"></i> Engagement</span>
                                        <h5>{{ $post['engagement'] ?? 0 }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning text-center">
                            No Facebook post data found.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>
@stop
