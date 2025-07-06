@extends('layouts.master')
@section('page_title', 'Maintenance Report')
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
                    <h3 class="page-title">Maintenance Report</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Fleet</a></li>
                        <li class="breadcrumb-item active">Maintenance Report</li>
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
                                <th>Car</th>
                                <th>Driver</th>
                                <th>Vendor</th>
                                <th>Region</th>
                                <th>Zone</th>
                                <th>Department</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Type</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th colspan="7"></th> <!-- Empty cells for the first 7 columns -->
                                <th id="totalNet" class="text-center">0</th> <!-- This will display the total net amount -->
                                <th colspan="2"></th> <!-- Empty cells for the last 2 columns -->
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
        $(document).ready(function() {
            var table = $('#tableMain').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('fleet.vehicle.maintenance.report') }}',
                    data: function(d) {
                        d.department_id = $('#department').val();
                        d.region_id = $('#region').val();
                        d.month = $('#month').val(); // Get the selected month
                    }
                },
                columns: [
                    { data: 'car', name: 'car' },
                    { data: 'driver', name: 'driver' },
                    { data: 'vendor_name', name: 'vendor_name' },
                    { data: 'region', name: 'region' },
                    { data: 'zone', name: 'zone' },
                    { data: 'department', name: 'department' },
                    { data: 'start_date', name: 'start_date' },
                    { data: 'net_total', name: 'net_total', render: $.fn.dataTable.render.number(',', '.', 2, 'GHS.') },
                    { data: 'type', name: 'type' },
                    { data: 'status', name: 'status' },
                ],
                dom: 'Bfrtip',
                buttons: [
                    { extend: 'copy', footer: true },
                    { extend: 'csv', footer: true },
                    { extend: 'pdf', footer: true },
                    { extend: 'print', footer: true }
                ],
                drawCallback: function() {
                    var api = this.api();
                    var totalNet = api.column(7).data().reduce(function(a, b) {
                        return parseFloat(a) + parseFloat(b) || 0;
                    }, 0);
                    $('#totalNet').html('Total:' + ' ' + 'GHS.' + totalNet.toFixed(2));
                }
            });
    
            // Show region filter based on department selection
            $('#department').on('change', function() {
                if ($(this).val() !== '') {
                    $('#regionFilter').show();
                } else {
                    $('#regionFilter').hide();
                    $('#region').val('');
                    table.ajax.reload();
                }
            });
    
            // Filter data on button click
            $('#btnFilter').on('click', function() {
                table.ajax.reload();
            });
    
            // Reload table when month changes
            $('#month').on('change', function() {
                table.ajax.reload(); // Refresh the DataTable when month changes
            });
        });
    </script>
    

@endsection
<script src="https://cdn.datatables.net/buttons/1.0.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.0.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.0.3/js/buttons.print.min.js"></script>
<script>
    

    $(document).ready(function() {
        var table = $('#tableMain').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('fleet.vehicle.diagnosis.report') }}',
                data: function(d) {
                    d.department_id = $('#department').val();
                    d.region_id = $('#region').val();
                    d.month = $('#month').val();
                }
            },
            columns: [
                { data: 'start_date', name: 'start_date' },
                { data: 'car', name: 'car' },
                { data: 'driver', name: 'driver' },
                { data: 'region', name: 'region' },
                { data: 'vendor_name', name: 'vendor_name' },
                { data: 'department', name: 'department' },
                { data: 'quantity', name: 'quantity' },
                { data: 'price', name: 'price', render: $.fn.dataTable.render.number(',', '.', 2, 'GHS.') },
                { data: 'description', name: 'description' },
            ],
            dom: 'Bfrtip',
            buttons: [
                { extend: 'copy', footer: true },
                { extend: 'csv', footer: true },
                { extend: 'pdf', footer: true },
                { extend: 'print', footer: true }
            ],
            drawCallback: function() {
                const api = this.api();
                const totalCost = api.column(7).data().reduce((a, b) => parseFloat(a) + parseFloat(b) || 0, 0);
                const totalQuantity = api.column(6).data().reduce((a, b) => parseInt(a) + parseInt(b) || 0, 0);

                $('#totalPrice').html('Total Cost: GHS.' + totalCost.toFixed(2));
                $('#totalQuantity').html('Total Quantity: ' + totalQuantity);
            }
        });
        $('#department').on('change', function() {
            if ($(this).val() !== '') {
                $('#regionFilter').show();
            } else {
                $('#regionFilter').hide();
                $('#region').val('');
                table.ajax.reload();
            }
        });

        // Filter data on button click
        $('#btnFilter').on('click', function() {
            table.ajax.reload();
        });

        // Reload table when month changes
        $('#month').on('change', function() {
            table.ajax.reload(); // Refresh the DataTable when month changes
        });
        
    });
</script>