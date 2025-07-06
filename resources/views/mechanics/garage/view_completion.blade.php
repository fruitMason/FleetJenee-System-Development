@extends('layouts.master')
@section('page_title', 'Garage - Completion')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">
@endsection
@section('content')
    <div class="page-wrapper">

        <div class="content container-fluid">

            <div class="col-md-12">@include('includes.error')</div>

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Garage - Completion</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Mechanic</a></li>
                            <li class="breadcrumb-item active">Garage - Completion</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row" id="card_content">
                <div class="col-md-12">
                    <div class="table-responsive">
                        {!! $dataTable->table(['class' => 'table table-striped custom-table']) !!}
                    </div>
                </div>
            </div>
 

            {{-- view invoice and payment --}}
            <div class="row">
                <div class="col-md-12">
                    <section class="panel panel-default">
                        <div class="card mg-b-20" id="card_content">
                            <div class="card-body">
                                <div class="card-title">Invoice Information </div>

                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <div class="title fw-bold">Invoice # </div>
                                        <div class="text"> {{ $invoice->invoice_number }}</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="fw-bold">Status:</div>
                                        @if ($invoice->status == 'paid')
                                            <div class="text-success"> {{ ucwords($invoice->status) }} </div>
                                        @else
                                            <div class="text-warning"> {{ ucwords($invoice->status) }} </div>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <div class="fw-bold">Invoice Amount</div>
                                        <div> {{ number_format($invoice->net_total, 2) }}

                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <div class="fw-bold">Due Date</div>
                                        <div> {{ $invoice->due_date->format('D d M, Y') }}</div>
                                    </div>

                                    <div class="col-md-8">
                                        <div class="fw-bold">Reference | Message</div>
                                        <div>
                                            {{ $invoice->reference }} | {{ $invoice->message }}
                                        </div>
                                    </div>

                                </div>
                                <div class="card-title">Payment Information </div>

                                <div class="table-responsive">
                                    <table class="table  table-hover table-sm">
                                        <thead class="bg-dark text-white">
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Date</th>
                                                <th scope="col">Amount Paid</th>
                                                <th scope="col">Payment Mode</th>
                                                <th scope="col">Reference</th>
                                                <th scope="col">Narration</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($payment as $p)
                                                <tr>
                                                    <th scope="row"> {{ $loop->iteration }}</th>
                                                    <td> {{ date('D, d F Y', strtotime($p->payment_date)) }} </td>
                                                    <td> @fmoney($p->amount_paid)</td>
                                                    <td>{{ $p->payment_mode }} </td>
                                                    <td>{{ $p->payment_reference }}</td>
                                                    <td> {{ $p->narration }} </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>

                        </div>

                    </section>
                </div>
            </div>
            {{-- //end of view invoice --}}
        </div>

    </div>

@endsection

@section('js')
    {!! $dataTable->scripts() !!}

    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>
@endsection
