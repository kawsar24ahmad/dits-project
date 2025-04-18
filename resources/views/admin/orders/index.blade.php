@extends('admin.layouts.app')

@section('content')

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        {{-- <h2 class="content-header-title float-left mb-0">Brand</h2> --}}
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a>
                                </li>
                                <li class="breadcrumb-item active"><a href="{{route('admin.orders.index')}}">Products</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <!-- Scroll - horizontal and vertical table -->
            <section id="horizontal-vertical">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row w-100 align-items-center">
                                    <div class="col">
                                        <h4 class="card-title text-bold text-lg mb-0">Orders List</h4>
                                    </div>
                                    <div class="col-auto text-end">
                                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary rounded text-white">
                                            <i class="fa fa-arrow"></i> Back
                                        </a>
                                    </div>
                                </div>
                            </div>




                            <div class="card-content">
                                <div class="card-body card-dashboard">
                                    <div class="table-responsive">
                                        <table class="table zero-configuration">
                                            <thead>
                                                <tr>
                                                    <th>Sl</th>
                                                    <th>Order Id</th>
                                                    <th>User Id</th>
                                                    <th>Service</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Transaction Id</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                    <th>Payment Time</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($orders as $order)
                                                <tr>
                                                    <td>{{$loop->iteration}}</td>
                                                    <td>{{$order->id}}</td>
                                                    <td>{{$order->user_id}}</td>
                                                    <td>{{$order->service->title}}</td>
                                                    <td>{{$order->name}}</td>
                                                    <td>{{$order->email}}</td>
                                                    <td>{{$order->phone}}</td>
                                                    <td>
                                                    @if ($order->transaction_id)
                                                        {{ $order->transaction_id }}
                                                        @else
                                                        N/A
                                                        @endif
                                                    </td>
                                                    <td>{{$order->amount}}</td>
                                                    <td>
                                                        @if ($order->status == "Pending")
                                                        <span class="badge badge-warning">Pending</span>
                                                        @elseif ($order->status == "Processing")
                                                        <span class="badge badge-info">Processing</span>
                                                        @elseif ($order->status == "Completed")
                                                        <span class="badge badge-success">Completed</span>
                                                        @elseif ($order->status == "Canceled")
                                                        <span class="badge badge-danger">Canceled</span>
                                                        @elseif ($order->status == "Refunded")
                                                        <span class="badge badge-danger">Refunded</span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                    @if ($order->payment_time)
                                                        {{ \Carbon\Carbon::parse($order->payment_time)->format('d-M-Y h:i:s') }}
                                                        @else
                                                        N/A
                                                        @endif

                                                    </td>
                                                    <td>

                                                        <a href="{{route('admin.orders.edit',$order->id)}}">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure to delete this order?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-link text-danger p-0 m-0 align-baseline" title="Delete">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        {{ $orders->appends(request()->query())->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--/ Scroll - horizontal and vertical table -->
        </div>
    </div>
</div>

@stop
