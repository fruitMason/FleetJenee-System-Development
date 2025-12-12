@extends('layouts.master')

@section('page_title', 'View Invoice')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">
    <style>
        .noborder td,
        .noborder th {
            border: none !important;
        }

        .container-fluid {
            padding: 0 15px;
        }

        .img-fluid {
            max-width: 100%;
            height: auto;
        }

        .text-right {
            text-align: right;
        }

        .float-end {
            float: right;
        }

        .bg-dark {
            background-color: #000;
            color: #fff;
        }

        .bg-orange {
            background-color: #FFA900;
            color: #fff;
        }

        .page-header,
        .section-title {
            margin-bottom: 20px;
        }

        .section-title h1 {
            font-size: 1.5rem;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .table thead th {
            vertical-align: bottom;
        }

        .table tbody+tbody {
            border-top: 2px solid #dee2e6;
        }

        .signature-section {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .signature-section .signature-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .signature-line {
            border-bottom: 1px solid #000;
            width: 100%;
            height: 30px;
            margin-top: 5px;
        }

        .signature-item span {
            margin-bottom: 5px;
        }

        .signature-item:last-child {
            margin-top: 30px;
        }

        .signature-section .signature-item.right {
            text-align: right;
        }

        .signature-section .signature-item.left {
            text-align: left;
        }

        .table-container {
            margin-top: 20px;
        }
    </style>
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
                    @if (request()->getHost() == 'test.autospagh.com' || request()->getHost() == '127.0.0.1')
                        <div class="col-auto float-end ms-auto">
                            <div class="btn-group btn-group-sm">
                                {{-- <button class="btn btn-white">CSV</button> --}}
                                <a href="{{ route('finance.invoice.print', request()->segment(count(request()->segments()))) }}"
                                    target="_blank" class="btn btn-primary">Print</a>
                                {{-- <button class="btn btn-white"><i class="fa fa-print fa-lg"></i> Print</button> --}}
                            </div>
                        </div>
                    @endif
                    {{-- Optional buttons can be added here if needed --}}
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="w-100 mb-4">
                                <tr>
                                    <td class="w-80">
                                        <img class="img-fluid" alt="AVATAR" src="{{ asset('assets/img/fleetjeneelogo.jpg') }}" width="200">
                                    </td>
                                    <td class="w-20 text-right">
                                        <h1 class="section-title">PURCHASE ORDER</h1>
                                    </td>
                                </tr>
                            </table>

                            <div class="mb-4">
                                <table class="table noborder">
                                    <thead>
                                        <tr class="bg-orange">
                                            <th>VENDOR(s)</th>
                                            <th>P.O NO</th>
                                            <th>ORDER DATE</th>
                                            <th>TIMELINE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $data->vendor->name }}</td>
                                            <td>{{ $data->invoice_number }}</td>
                                            <td>{{ \Carbon\Carbon::parse($data->created_at)->format('Y-m-d') }}</td>
                                            <td>{{ date('Y-m-d', strtotime($data->due_date)) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <hr>

                            <div class="mb-4">
                                <table class="w-100">
                                    <tr>
                                        <td>
                                            <b>FROM</b>:<br>
                                            {{ $data->vendor->name }}<br>
                                            {{ $data->vendor->email }}<br>
                                            {{ $data->vendor->phone_number }}<br>
                                            {{ $data->vendor->address }}<br>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <hr>

                            <div class="mb-4">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th>ITEM</th>
                                                <th>INVOICE NO.</th>
                                                <th>Qty</th>
                                                <th>UNIT PX</th>
                                                <th>AMOUNT</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data->invoiceItem as $item)
                                                <tr>
                                                    <td>{{ $item->description }}</td>
                                                    <td>{{ $item->invoice->invoice_number }}</td>
                                                    <td>{{ $item->price }}</td>
                                                    <td>{{ $item->quantity }}</td>
                                                    <td>{{ $item->total }}</td>
                                                </tr>
                                            @endforeach

                                            <tr class="noborder bg-light">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="font-weight-bold">SUB TOTAL :</td>
                                                <td>{{ number_format($data->sub_total, 2) }}</td>
                                            </tr>

                                            <tr class="noborder bg-light">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="font-weight-bold">VAT</td>
                                                <td> {{ $data->vat_total }}</td>
                                            </tr>

                                            <tr class="noborder bg-dark">
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td class="font-weight-bold">INVOICE TOTAL :</td>
                                                <td class="font-weight-bold">{{ number_format($data->net_total, 2) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="signature-section">
                                    <div class="signature-item left">
                                        <span><strong>Authorised Signatory,</strong></span>
                                        <div class="signature-line"></div>
                                    </div>
                                    <div class="signature-item right">
                                        <span><strong>Receiver's Seal & Sign</strong></span>
                                        <div class="signature-line"></div>
                                    </div>
                                </div>
                            </div>

                            <table class="w-100 mb-4">
                                <tr>
                                    <td class="w-80">
                                        <img class="img-fluid" alt="AVATAR"
                                            src="{{ asset('assets/img/fleetjeneelogo.jpg') }}" width="200">
                                    </td>
                                    <td class="w-20 text-right">
                                        <h1 class="section-title">WORK ORDER</h1>
                                    </td>
                                </tr>
                            </table>



                            <div class="mb-4">
                                <table class="w-100">
                                    <tr>
                                        <td>
                                            <b>TO</b>:<br>
                                            {{ $work_data->mechanic ? $work_data->mechanic->full_name() : 'N/A' }}<br>
                                            {{ $work_data->mechanic ? $work_data->mechanic->email : 'N/A' }}<br>
                                            {{ $work_data->mechanic ? $work_data->mechanic->mobile : 'N/A' }}<br>
                                            {{-- {{ $work_data->mechanic && $data->maintenance->mechanic->department && $data->maintenance->mechanic->department->region ? $data->maintenance->mechanic->department->region->name : 'N/A' }} --}}

                                            <br>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <hr>
                            <h3>Order detail</h3>

                            <div class="row">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="title"><strong>Car:</strong></div>
                                        <div class="text">{{ $work_data->car->model ?? 'N/A' }}
                                            ({{ $work_data->car->car_number ?? 'N/A' }}) </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div><strong>Maintenance Type:</strong></div>
                                        <div>{{ $work_data->type ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </div>


                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div> <strong>Start Date:</strong> </div>
                                    <div>
                                        {{ $work_data->start_date ? $work_data->start_date->format('D, d F Y H:i A') : 'N/A' }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div><strong>End Date:</strong></div>
                                    <div>
                                        {{ $work_data->end_date ? $work_data->end_date->format('D, d F Y H:i A') : 'N/A' }}
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <div><strong>Comments:</strong></div>
                                    <div>{{ $work_data->comment ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <hr>
                            <div class="mb-4">
                                {{-- <div class="table-responsive">
                                    <table class="table table-striped table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th>VEHICLE TYPE</th>
                                                <th>REG NO.</th>
                                                <th>DESCRIPTION</th>
                                                <th>MILEAGE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data->invoiceItem as $item)
                                                <tr>
                                                    <td>{{ $item->car->model ?? 'NA/' }}</td>
                                                    <td>{{ $item->car->car_number ?? 'NA/' }}</td>
                                                    <td>{{ $item->description ?? 'NA/' }}</td>
                                                    <td>{{ $item->car->engine_capacity ?? 'NA/' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div> --}}

                                <div class="signature-section">
                                    <div class="signature-item left">
                                        <span><strong>Authorised Signatory,</strong></span>
                                        <div class="signature-line"></div>
                                    </div>
                                    <div class="signature-item">
                                        <span><strong>DRIVER NAME</strong></span>
                                        <div class="signature-line"></div>
                                    </div>
                                    <div class="signature-item">
                                        <span><strong>CONTACT</strong></span>
                                        <div class="signature-line"></div>
                                    </div>
                                    <div class="signature-item">
                                        <span><strong>SIGN</strong></span>
                                        <div class="signature-line"></div>
                                    </div>
                                    <div class="signature-item right">
                                        <span><strong>Receiver's Seal & Sign</strong></span>
                                        <div class="signature-line"></div>
                                    </div>
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
