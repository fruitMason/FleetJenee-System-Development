@extends('layouts.master')
@section('page_title', 'Diagnosis Report')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.0.3/css/buttons.dataTables.min.css">
@endsection

@section('content')
<div class="page-wrapper">
    <div class="content container-fluid">
        <div class="col-md-12">@include('includes.error')</div>

        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Diagnosis Report</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Fleet</a></li>
                        <li class="breadcrumb-item active">Diagnosis Report</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row filter-row">
            <div class="col-sm-6 col-md-3">
                <div class="form-group form-focus select-focus">
                    <select class="select floating" id="department">
                        <option value="">-- Select Department --</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                    <label class="focus-label">Department</label>
                </div>
            </div>
        </div>

        <div class="row filter-row" id="regionFilter" style="display: none;">
            <div class="col-sm-6 col-md-3">
                <div class="form-group form-focus select-focus">
                    <select class="select floating" id="region">
                        <option value="">-- Select Region --</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}">{{ $region->name }}</option>
                        @endforeach
                    </select>
                    <label class="focus-label">Region</label>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <button type="button" id="btnFilter" class="btn btn-success w-100">Filter</button>
            </div>
        </div>

        <div class="row filter-row">
            <div class="col-sm-6 col-md-3">
                <div class="form-group form-focus select-focus">
                    <select class="select floating" id="month">
                        <option value="">-- Select Month --</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}">{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                        @endfor
                    </select>
                    <label class="focus-label">Month</label>
                </div>
            </div>
        </div>

        <div class="row" id="card_content">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table id="tableMain" class="table table-striped custom-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Car</th>
                                <th>Driver</th>
                                <th>Vendor Name</th>
                                <th>Region</th>
                                <th>Department</th>
                                <th>Quantity</th>
                                <th>Cost</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="6" class="text-right"></th>
                                <th class="text-right" id="total-quantity">0</th>
                                <th colspan="1" class="text-right" id="total-price">0</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
    {!! $dataTable->scripts() !!}


    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.0.3/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.0.3/js/buttons.print.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize DataTable
            var table = $('#tableMain').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('fleet.vehicle.diagnosis.report') }}',
                    data: function (d) {
                        d.department_id = $('#department').val();
                        d.region_id = $('#region').val();
                        d.month = $('#month').val();
                    }
                },
                columns: [
                    { data: 'start_date', name: 'start_date', className: 'text-center' },
                    { data: 'car', name: 'car', className: 'text-center' },
                    { data: 'driver', name: 'driver', className: 'text-center' },
                    { data: 'vendor_name', name: 'vendor_name', className: 'text-center' },
                    { data: 'region', name: 'region', className: 'text-center' },
                    { data: 'department', name: 'department', className: 'text-center' },
                    { data: 'quantity', name: 'quantity', className: 'text-center' },
                    { data: 'price', name: 'price', className: 'text-center',  render: $.fn.dataTable.render.number(',', '.', 2, 'GHS.') },
                    { data: 'description', name: 'description', className: 'text-center' }
                ],
                dom: 'Bfrtip', // This positions the buttons
                buttons: [
                    { extend: 'copy', footer: true },
                    { extend: 'csv', footer: true },
                    { extend: 'pdf', footer: true },
                    { extend: 'print', footer: true }
                ],
                drawCallback: function () {
                    var api = this.api();
                    var totalQuantity = api.column(6).data().reduce(function (a, b) {
                        return parseInt(a) + parseInt(b) || 0;
                    }, 0);
                    var totalPrice = api.column(7).data().reduce(function (a, b) {
                        return parseFloat(a) + parseFloat(b) || 0;
                    }, 0);
                    $('#total-quantity').html('Total Quantity' + ' ' + totalQuantity);
                    $('#total-price').html('Total:' + ' ' + 'GHS.' + totalPrice.toFixed(2));
                }
            });
        
            // Toggle region filter based on department selection
            $('#department').change(function () {
                $('#regionFilter').toggle($(this).val() !== '');
            });
        
            // Reload the DataTable on filter button click
            $('#btnFilter').click(function () {
                table.ajax.reload();
            });
        
            // Reload the DataTable when the month changes
            $('#month').change(function () {
                table.ajax.reload();
            });
        });
    </script>
    
@endsection
