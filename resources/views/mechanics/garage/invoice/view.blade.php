@extends('layouts.master')
@section('page_title', 'View Invoice')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">
@endsection
@section('content')

    <div class="page-wrapper">

        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Invoice</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="admin-dashboard.html">Dashboard</a></li>
                            <li class="breadcrumb-item active">Invoice</li>
                        </ul>
                    </div>
                    <div class="col-auto float-end ms-auto">
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-white">CSV</button>
                            <button class="btn btn-white">PDF</button>
                            <button class="btn btn-white"><i class="fa fa-print fa-lg"></i> Print</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6 m-b-20">
                                    <img src="assets/img/logo2.png" class="inv-logo" alt="">
                                    <ul class="list-unstyled">
                                        <li>Goil autoSpa</li>
                                        <li>3864 Quiet Valley Lane,</li>
                                        <li>Sherman Oaks, CA, 91403</li>
                                    </ul>
                                </div>
                                <div class="col-sm-6 m-b-20">
                                    <div class="invoice-details">
                                        <h3 class="text-uppercase">Invoice #{{$invoice->invoice_number}}</h3>
                                        <ul class="list-unstyled">
                                            <li>Date: <span>{{$invoice->created_at->format('D, d F Y')}}</span></li>
                                            <li>Due date: <span>{{\Carbon\Carbon::parse($invoice->due_date)->format('D, d F Y')}}</span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 col-lg-7 col-xl-8 m-b-20">
                                    <h5>Invoice to:</h5>
                                    <ul class="list-unstyled">
                                        <li><h5><strong>{{$invoice->vendor->name}}</strong></h5></li>
                                        <li><span>{{$invoice->vendor->service_type}}</span></li>
                                        <li>{{$invoice->vendor->address}}</li>
                                        <li><a href="tel:{{$invoice->vendor->phone_number}}">{{$invoice->vendor->phone_number}}</a></li>
                                        <li><a href="mailto:{{$invoice->vendor->email}}">{{$invoice->vendor->email}}</a></li>
                                    </ul>
                                </div>
                                <div class="col-sm-6 col-lg-5 col-xl-4 m-b-20">
                                    <span class="text-muted">Payment Details:</span>
                                    <ul class="list-unstyled invoice-payment-details">
                                        <li><h5>Total Due: <span class="text-end">{{number_format($invoice->net_total)}}</span></h5></li>
{{--                                        <li>Bank name: <span>Profit Bank Europe</span></li>--}}
{{--                                        <li>Country: <span>United Kingdom</span></li>--}}
{{--                                        <li>City: <span>London E1 8BF</span></li>--}}
{{--                                        <li>Address: <span>3 Goodman Street</span></li>--}}
{{--                                        <li>IBAN: <span>KFH37784028476740</span></li>--}}
{{--                                        <li>SWIFT code: <span>BPT4E</span></li>--}}
                                    </ul>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th class="d-none d-sm-table-cell">ITEM</th>
                                        <th>UNIT COST</th>
                                        <th>QUANTITY</th>
                                        <th>TAX</th>
                                        <th class="text-end">TOTAL</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($invoice->invoiceItem as $item)
                                    <tr>
                                        <td>{{$item->id}}</td>
                                        <td class="d-none d-sm-table-cell">{{$item->description}}</td>
                                        <td>{{number_format($item->price)}}</td>
                                        <td>{{number_format($item->quantity)}}</td>
                                        <td>{{number_format($item->tax_amount)}}</td>
                                        @php $total = $item->tax_amount + $item->total @endphp
                                        <td class="text-end">{{number_format($total)}}</td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div>
                                <div class="row invoice-payment">
                                    <div class="col-sm-7">
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="m-b-20">
                                            <div class="table-responsive no-border">
                                                <table class="table mb-0">
                                                    <tbody>
                                                    <tr>
                                                        <th>Subtotal:</th>
                                                        <td class="text-end">{{number_format($invoice->sub_total)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Tax: <span class="text-regular">(25%)</span></th>
                                                        <td class="text-end">{{number_format($invoice->tax_total)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total:</th>
                                                        <td class="text-end text-primary"><h5>{{number_format($invoice->net_total)}}</h5></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="invoice-info">
                                    <h5>Other information</h5>
                                    <p class="text-muted">{{$invoice->message}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('js')
@endsection
