<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ $data->invoice_number . '_' . $data->vendor->name }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/pdf/bootstrap.min.css') }}">
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
</head>

<body>

    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td>
                    <h1 class="section-title">PURCHASE ORDER</h1>
                </td>
                <td class="text-right"><img class="img-fluid" alt="Logo"
                        src="{{ public_path('assets/img/fleetjeneelogo.jpg') }}" width="200"></td>
            </tr>
        </table>
    </div>

    <div class="col-md-12">
        <table class="table noborder">
            <tr class="bg-orange" style="font-weight:bold; font-size: 18px;">
                <td>VENDOR(s)</td>
                <td>P.O NO</td>
                <td>ORDER DATE</td>
                <td>TIMELINE</td>
            </tr>
            <tr>
                <td>{{ $data->vendor->name }}</td>
                <td>{{ $data->invoice_number }}</td>
                <td>{{ \Carbon\Carbon::parse($data->created_at)->format('Y-m-d') }}</td>
                <td>{{ date('Y-m-d', strtotime($data->due_date)) }}</td>
            </tr>
        </table>
    </div>

    <hr>

    <div class="col-md-12">
        <table>
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

    <div class="col-md-12">
        <table class="table table-striped">
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
                <tr class="noborder">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="font-weight:bold">SUB TOTAL :</td>
                    <td>{{ number_format($data->sub_total, 2) }}</td>
                </tr>
                <tr class="noborder">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="font-weight:bold">VAT</td>
                    <td>{{ $data->vat_total }}</td>
                </tr>
                <tr class="bg-dark">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="font-weight:bold">INVOICE TOTAL :</td>
                    <td style="font-weight:bold">{{ number_format($data->net_total, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="signature-section">
            <div>
                <span><strong>Authorised Signatory,</strong></span>
                <div class="signature-line"></div>
            </div>
            <div>
                <span><strong>Receiver's Seal & Sign</strong></span>
                <div class="signature-line"></div>
            </div>
        </div>
    </div>

    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td>
                    <h1 class="section-title">WORK ORDER</h1>
                </td>
                <td class="text-right"><img class="img-fluid" alt="Logo"
                        src="{{ asset('assets/img/fleetjeneelogo.jpg') }}" width="200"></td>
            </tr>
        </table>
    </div>


    <div class="col-md-12">
        <table>
            <tr>
                <td>
                    <b>TO</b>:<br>
                    {{-- {{ $data->maintenance->mechanic->full_name() ?? ' NA' }}<br>
                    {{ $data->maintenance->mechanic->email }}<br>
                    {{ $data->maintenance->mechanic->mobile ?? 'N/A' }}<br>
                    {{ $data->maintenance->mechanic->department->region->name ?? 'N/A' }}<br> --}}
                </td>
            </tr>
        </table>
    </div>

    <hr>

    <div class="col-md-12">
        {{-- <table class="table table-striped">
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
        </table> --}}

        <div class="signature-section">
            <div>
                <span><strong>Authorised Signatory,</strong></span>
                <div class="signature-line"></div>
            </div>
            <div>
                <span><strong>Receiver's Seal & Sign</strong></span>
                <div class="signature-line"></div>
            </div>
        </div>
    </div>

</body>

</html>
