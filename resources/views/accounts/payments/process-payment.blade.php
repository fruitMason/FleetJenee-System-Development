@extends('layouts.master')

@section('page_title', 'Payment Processing')

{{-- @section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">
@endsection --}}

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="col-md-12">
                @include('includes.error')
            </div>

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Payment Processing</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"> <a href="{{ route('accounts.payment.requests') }}">Payment
                                    Requests</a> </li>
                            <li class="breadcrumb-item active">Process Payment</li>
                        </ul>
                    </div>
                    <div class="col-auto float-end ms-auto">
                        <a href="{{ route('accounts.payment.requests') }}" class="btn add-btn"><i
                                class="fa fa-arrow-left"></i> Back
                            to List</a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <section class="panel panel-default">
                        <div class="card mg-b-20" id="card_content">
                            <div class="card-body">
                                <div class="card-title">Request Detail </div>

                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <div class="title">Request Type:</div>
                                        <div class="text fw-bold"> {{ ucwords($request->payment_type) }}</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div>Status:</div>
                                        @if ($request->status == 'paid')
                                            <div class="text-danger"> {{ ucwords($request->status) }} </div>
                                        @else
                                            <div class="text-success"> {{ ucwords($request->status) }} </div>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <div class="fw-bold">Amout Requested | Paid | Balance</div>
                                        <div> {{ number_format($request->amount, 2) }} |
                                            {{ number_format($request->amount_paid, 2) }} |
                                            {{ number_format($request->amount - $request->amount_paid, 2) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <div>Request Date</div>
                                        <div> {{ $request->request_date }}</div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="text-bold">Requested By </div>
                                        <div>
                                            {{ $request->user->full_name() }}
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="text-bold">In Favor Of</div>
                                        <div> {{ $request->for_user ? $request->for_user->full_name() : 'N/A' }}
                                            {{ $request->car_assigned == 'Unassigned' ? ': FM' : 'Assigned' }}
                                        </div>
                                    </div>



                                </div>

                                <div class="row ">

                                    @if ($request->payment_type == 'maintenance')
                                        <div class="col-md-4">
                                            <div>Car Info</div>
                                            <div>
                                                {{ $request->car->car_features() }}
                                            </div>
                                        </div>
                                    @endif


                                    <div class="col-md-8">
                                        <div>Description</div>
                                        <div> {{ $request->description }}</div>
                                    </div>

                                </div>
                            </div>

                        </div>

                    </section>
                </div>
            </div>

            {{-- Payment Details --}}
            <section class="panel panel-default">
                <div class="card mg-b-20" id="card_content">
                    <div class="card-body">
                        <div class="card-title">Payment Detail </div>

                        <form method="post" action="{{ route('accounts.payment.process.pay') }}"
                            onsubmit="return SubmitDelete(this,'Process {{ $request->payment_type }}');"
                            class="confirmationForm" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="{{ $request->id }}">

                            @csrf
                            <!-- First Row - 3 equal columns -->
                            <div class="row">
                                <!-- Input 1 -->
                                <div class="col-md-4">
                                    <div class="form-group form-focus select-focus">
                                        Payment Date :
                                        <input type="date" name="payment_day" class="form-control"
                                            value="{{ old('payment_day') }}" required>
                                    </div>
                                </div>

                                <!-- Input 2 -->
                                <div class="col-md-4">
                                    <div class="form-group form-focus select-focus">
                                        Payment Amount <span class="text-danger">*</span>
                                        <input type="number" name="amount" id="amount" step="0.1"
                                            class="form-control" required
                                            value="{{ old('amount', $request->amount - $request->amount_paid) }}">
                                    </div>
                                </div>

                                <!-- Input 3 -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="col-md-12">Request Type
                                            Payment Mode
                                            <span class="text-danger">*</span>
                                            <select class="select floating" name="payment_mode" required>
                                                <option value="">Select Payment Mode *</option>
                                                @foreach ($payment_mode as $yn)
                                                    @if (old('payment_mode') == $yn)
                                                        <option value="{{ $yn }}" selected>
                                                            {{ $yn }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $yn }}">
                                                            {{ $yn }}
                                                        </option>
                                                    @endif
                                                @endforeach


                                            </select>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- 2nd Row -  equal columns -->
                            <div class="row mb-2">
                                <!-- Input 1 -->
                                <div class="col-md-8">
                                    <div class="">
                                        Naration<span class="text-danger">*</span>
                                        <textarea class="form-control" name="naration" id="naration" rows="3" required>{{ old('naration', $request->description . ' full payment') }}</textarea>

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-focus select-focus">
                                        Ref No/Cheque No
                                        <input type="text" name="ref" id="ref" class="form-control"
                                            value="{{ old('ref') }}">
                                    </div>
                                </div>
                            </div>

                            <!-- 3rd Row -  equal columns -->
                            <div class="">
                                <button class="btn btn-primary submit-btn" type="submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>

            </section>
            {{-- //end Payment Details --}}


            {{-- Auto Parts Details --}}
            @if ($request->payment_type == 'auto part')
                <section class="panel panel-default">
                    <div class="card">
                        <div class="card-header card-title">
                            Auto Parts Summary
                        </div>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Auto Part</th>
                                    <th scope="col">Quanity</th>
                                    <th scope="col">TotalAmount</th>
                                    <th scope="col">Unit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoice_items as $item)
                                    <tr>
                                        @php
                                            $total = $item->total + $item->tax_amount;
                                            $unit = $total / $item->quantity;
                                        @endphp
                                        <th scope="row"> {{ $loop->index }} </th>
                                        <td>{{ $item->name }},</td>
                                        <td>{{ $item->quantity }} </td>
                                        <td> {{ number_format($total, 2) }}</td>
                                        <td> {{ number_format($unit, 2) }}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>


                    </div>
                </section>
            @endif
            {{-- //Auto Parts Details --}}


        </div>
    </div>
@endsection
