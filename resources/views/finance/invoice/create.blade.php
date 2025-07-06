@extends('layouts.master')
@section('page_title', 'Create Invoice')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">

    <link rel="stylesheet" href="{{ asset('js/jsgrid/jsgrid.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('js/jsgrid/jsgrid-theme.min.css') }}" />
@endsection
@section('content')
    <div class="page-wrapper">

        <div class="content container-fluid">

            {{--            <div class="col-md-12">@include('includes.error')</div> --}}
            <div id="errorMsg" style="display: none" class="alert alert-danger"></div>

            <form role="form" onsubmit="return false;" method="post" id="formInvoice" class="form-horizontal"
                enctype="multipart/form-data">
                <div class="page-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="page-title">Create Invoice</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Finance</a></li>
                                <li class="breadcrumb-item active">Create Invoice</li>
                            </ul>
                        </div>
                        <div class="col-auto float-end ms-auto">
                            <button type="submit" class="btn btn-primary submit-btn m-r-10">Submit</button>
                        </div>
                        <div class="col-auto float-end ms-auto">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Vendor <span class="text-danger">*</span></label>
                                    <select class="select" name="vendor_id" id="vendorSelect">
                                        <option value="0">-- select vendor --</option>
                                        @foreach ($vendors as $vendor)
                                            <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Invoice Number Type <span class="text-danger">*</span></label>
                                    <select class="select" name="invoice_number_type" id="invoice_number_type">
                                        <option value="auto" selected>AUTO GENERATED</option>
                                        <option value="manual">MANUAL</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Invoice Number</label>
                                    <input id="invoice_number" name="invoice_number" class="form-control" type="text"
                                        readonly>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Due Date <span class="text-danger">*</span></label>
                                    <div class="cal-icon">
                                        <input id="due_date" name="due_date" class="form-control datetimepicker"
                                            type="text">
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label>Maintenance (Work Order) <span class="text-danger">*</span></label>
                                    <select class="select user-search2" name="maintenance_id" id="search2" required>
                                        <option value="">Select an Item</option>
                                        @foreach ($maintenances as $maintenance)
                                            <option @if (request()->get('maintenance_id') == $maintenance->id) selected @endif
                                                value="{{ $maintenance->id }}">{{ $maintenance->car->model }}
                                                ({{ $maintenance->car->car_number }})
                                                -
                                                {{ $maintenance->start_date->format('d-m-Y') }}
                                                [{{ $maintenance->comment }}]
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Reference</label>
                                    <textarea id="reference" name="reference" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label>Message on Invoice (Remarks)</label>
                                    <textarea id="message" name="message" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                        </div>

                        <hr class="mt0 mb15" />
                        <div class="row" id="row_item_table">
                            <div class="col-md-12">

                                <div class="table-responsive">
                                    <table id="mainTable"
                                        class="table table-hover table-striped text-center table-bordered">
                                        <thead>
                                            <tr class="text-center">
                                                <th>Item</th>
                                                <th>Desc.</th>
                                                <th>Unit Price</th>
                                                <th>Quantity</th>
                                                <th>Tax</th>
                                                <th>Total Amt.</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody" style="width: 200px">
                                        </tbody>
                                    </table>
                                    <a href="javascript:void(0);" class="btn btn-dark ml-3 mt-3 mb-3"
                                        id="add_prod_btn"><span class="fa fa-plus-circle"></span> Add Line Item</a>
                                </div>
                            </div>
                        </div>

                        <div class="row" style="padding-top: 10px">
                            <div class="col-md-3 form-inline">
                                <label class="col-form-label">VAT Total</label>
                                <input class="form-control col-md-8" id="vat_total" disabled name="vat_total">
                            </div>
                            <div class="col-md-3 form-inline">
                                <label class="col-form-label">GetFund Total</label>
                                <input class="form-control col-md-8" id="getfund_total" disabled name="getfund_total">
                            </div>
                            <div class="col-md-3 form-inline">
                                <label class="col-form-label">NHIL Total</label>
                                <input class="form-control col-md-8" id="nhil_total" disabled name="nhil_total">
                            </div>
                            <div class="col-md-3 form-inline">
                                <label class="col-form-label">COVID -19 LEVY</label>
                                <input class="form-control col-md-8" id="covid_total" disabled name="covid_total">
                            </div>

                        </div>
                        <div class="row mt-5">
                            <div class="col-md-3 form-inline">
                                <label class="col-form-label">CST Total</label>
                                <input class="form-control col-md-8" id="cst_total" disabled name="cst_total">
                            </div>
                            <div class="col-md-3 form-inline">
                                <label class="col-form-label">VAT Flat Total</label>
                                <input class="form-control col-md-8" id="vat_flat_total" disabled name="vat_">
                            </div>
                            <div class="col-md-3 form-inline">
                                <label class="col-form-label">Subtotal</label>
                                <input class="form-control col-md-8" id="all_sub_total" disabled name="subtotal">
                            </div>
                            <div class="col-md-3 form-inline">
                                <label class="col-form-label">TAX total</label>
                                <input class="form-control col-md-8" id="all_tax" disabled>
                            </div>
                            <div class="col-md-3 form-inline">
                                <label class="col-form-label">Net total</label>
                                <input class="form-control col-md-8" id="all_net" disabled>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>

@endsection

@section('js')

    <script type="text/javascript" src="{{ asset('js/jsgrid/jsgrid.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jsgrid/jsgrid.custom.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jsgrid/js_grid_handler.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>

    @include('finance.invoice.scripts')

    <script>
        $('#invoice_number_type').on('change', function() {
            let val = $(this).val();
            let invoice_number = $('#invoice_number');

            if (val === 'manual') {
                invoice_number.removeAttr('readonly')
            } else {
                invoice_number.attr('readonly', 'readonly')
            }
        });


        $(document).ready(function() {
            console.log('document ready');

            $('.user-search2').select2();
            // $('.car-search2').select2();

            
        });


        //populate work order
        // $(document).ready(function() {
        //     $('#vendorSelect').change(function() {
        //         var vendorId = $(this).val();
        //         console.log('vendorId', vendorId);
        //         if (vendorId) {
        //             // Make AJAX request
        //             $.ajax({
        //                 url: '/finance/invoice/get-orders-by-vendor', // Your route URL
        //                 type: 'GET',
        //                 data: {
        //                     vendor_id: vendorId
        //                 },
        //                 dataType: 'json',
        //                 success: function(data) {
        //                     $('#itemSelect').empty();
        //                     console.log('data', data);
        //                     return;

        //                     if (data.length > 0) {
        //                         $('#itemSelect').append(
        //                             '<option value="">Select an Item</option>');
        //                         $.each(data, function(key, value) {
        //                             $('#itemSelect').append('<option value="' + value
        //                                 .id + '">' + value.name + '</option>');
        //                         });
        //                         $('#itemSelect').prop('disabled', false);
        //                     } else {
        //                         $('#itemSelect').append(
        //                             '<option value="">No items available</option>');
        //                         $('#itemSelect').prop('disabled', true);
        //                     }
        //                 },
        //                 error: function(xhr, status, error) {
        //                     console.error(error);
        //                     $('#itemSelect').empty().append(
        //                         '<option value="">Error loading items</option>');
        //                 }
        //             });
        //         } else {
        //             $('#itemSelect').empty().append('<option value="">Select an Item</option>');
        //             $('#itemSelect').prop('disabled', true);
        //         }
        //     });
        // });
    </script>
@endsection
