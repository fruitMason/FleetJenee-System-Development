@extends('layouts.master')
@section('page_title', 'Overdue Report')
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
                        <h3 class="page-title">Overdue Report Odometers</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Fleet</a></li>
                            <li class="breadcrumb-item">Overdue Odometers</li>
                            <li class="breadcrumb-item active">Report</li>
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
            
            <div class="row" id="card_content">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="tableMain" class="table table-striped custom-table">
                            <thead>
                                <tr>
                                    <th>Last Input Date</th>
                                    <th>Value</th>
                                    <th>Car Model</th>
                                    <th>Car Number</th>
                                    <th>Region</th>
                                    <th>Department</th>
                                    <th>Assigned User</th>
                                    <th>Phone Number</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th colspan="1"></th> <!-- Empty cell for Last Input Date -->
                                    <th>Total:</th> <!-- This is the total cell -->
                                    <th colspan="6"></th> <!-- Empty cells for the rest -->
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
            // Check if DataTable is already initialized and destroy if necessary
            if ($.fn.dataTable.isDataTable('#tableMain')) {
                $('#tableMain').DataTable().destroy();
            }
    
            // Initialize DataTable
            var table = $('#tableMain').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('fleet.vehicle.odometer.report') }}',
                    data: function(d) {
                        d.department_id = $('#department').val();
                        d.region_id = $('#region').val();
                    }
                },
                columns: [
                    { data: 'created_at', name: 'created_at' },
                    { data: 'new_value', name: 'new_value' },
                    { data: 'car_model', name: 'car_model' },
                    { data: 'car_number', name: 'car_number' },
                    { data: 'region', name: 'region' },
                    { data: 'department', name: 'department' },
                    { data: 'assigned_user', name: 'assigned_user' },
                    { data: 'assigned_user_mobile', name: 'assigned_user_mobile' },
                ],
                dom: 'Bfrtip', // Important for buttons
                buttons: [
                    {
                        extend: 'copy',
                        footer: true // Include footer in copy
                    },
                    {
                        extend: 'csv',
                        footer: true, // Include footer in csv
                        exportOptions: {
                            format: {
                                body: function (data, row, column, node) {
                                    if (column === 1 && row === table.rows().count() - 1) {
                                        var total = table.column(1).data().reduce(function(a, b) {
                                            return parseFloat(a) + parseFloat(b) || 0;
                                        }, 0);
                                        return 'Total: ' + total.toFixed(2);
                                    }
                                    return data;
                                }
                            }
                        }
                    },
                    {
                        extend: 'pdf',
                        footer: true, // Include footer in pdf
                        exportOptions: {
                            format: {
                                body: function (data, row, column, node) {
                                    if (column === 1 && row === table.rows().count() - 1) {
                                        var total = table.column(1).data().reduce(function(a, b) {
                                            return parseFloat(a) + parseFloat(b) || 0;
                                        }, 0);
                                        return 'Total: ' + total.toFixed(2);
                                    }
                                    return data;
                                }
                            }
                        }
                    },
                    {
                        extend: 'print',
                        footer: true // Include footer in print
                    }
                ],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();
                    var total = api.column(1).data().reduce(function(a, b) {
                        return parseFloat(a) + parseFloat(b) || 0; // Sum up the new_value column
                    }, 0);
                    $(api.column(1).footer()).html('Total: ' + total.toFixed(2)); // Update footer with total
                }
            });
    
            // Show region filter based on department selection
            $('#department').on('change', function() {
                if ($(this).val() !== '') {
                    $('#regionFilter').show();
                } else {
                    $('#regionFilter').hide();
                    $('#region').val('');
                    table.ajax.reload(); // Refresh data table
                }
            });
    
            // Filter data on button click
            $('#btnFilter').on('click', function() {
                table.ajax.reload();
            });
        });
    </script>
    
    
    
@endsection
