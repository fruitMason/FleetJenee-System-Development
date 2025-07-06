@extends('layouts.master')
@section('page_title', 'Accounts - Payments')
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
                        <h3 class="page-title">Accounts - Payments</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Accounts</a></li>
                            <li class="breadcrumb-item active">Payments</li>
                        </ul>
                    </div>
                </div>
            </div>


            <div class="row filter-row">
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <div class="cal-icon">
                            <input class="form-control floating datetimepicker" type="text" id="from">
                        </div>
                        <label class="focus-label">From</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <div class="cal-icon">
                            <input class="form-control floating datetimepicker" type="text" id="to">
                        </div>
                        <label class="focus-label">To</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus select-focus">
                        <select class="select floating" id="status">
                            <option>-- Select Status --</option>
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="partially_paid">Partially Paid</option>
                        </select>
                        <label class="focus-label">Status</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <button type="button" id="btnFilter" class="btn btn-success w-100"> Filter </button>
                </div>
            </div>

            <div class="row" id="card_content">
                <div class="col-md-12">
                    <div class="table-responsive">
                        {!! $dataTable->table(['class' => 'table table-striped custom-table']) !!}
                    </div>
                </div>
            </div>
        </div>

        @include('modal.update_invoice_status')
    </div>

@endsection

@section('js')
    {!! $dataTable->scripts() !!}

    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>

    <script>

        modal_select_search($('.create_select_search'), $('#form_create'));

    </script>
    <script>
    
        function approveNotify(id, user_id) {
            waitme('card_content');
            setTimeout(function() {
                hidewaitme('card_content');
                $('#approve_car_modal .modal-title').html('Approving Car Request');
                $('#approve_car_request_id').val(id);
                $('#approve_car_user_id').val(user_id);
                $('#approve_car_modal').modal('show');
            }, 500);
        }
    
        function approve() {
            var id = $('#approve_car_request_id').val();
            // Assuming you want to allow the driver to approve without a reason
            ajax('{{ route('driver.vehicle.request.approve') }}', {
                car_request_id: id,
                _token: _token
            }, 'card_content', function(response) {
                if (response.code === 200) {
                    show_toast('Success!', response.message, 'success');
                    refreshPage(); // Refresh the page or dataTable
                } else {
                    show_toast('Error!', response.message, 'error');
                }
            });
        }
    
        function rejectNotify(id) {
            swal({
                title: "<small>Reason for rejecting this car request?</small>",
                input: "textarea",
                showCancelButton: true,
                confirmButtonColor: "#3858f9",
                confirmButtonText: "Proceed!",
                cancelButtonText: "No, cancel!",
                closeOnConfirm: false,
                closeOnCancel: true,
                showLoaderOnConfirm: true,
                inputPlaceholder: "Type in a reason...",
                inputValidator: function(reason) {
                    return new Promise(function(resolve, reject) {
                        swal.close();
                        reject_(id, reason);
                    });
                }
            });
        }
    
        function reject_(id, reason) {
            ajax('{{ route('driver.vehicle.request.reject') }}', {
                car_request_id: id,
                reason: reason,
                _token: _token
            }, 'card_content', function(response) {
                if (response.code === 200) {
                    show_toast('Success!', response.message, 'success');
                    refreshPage(); // Refresh the page or dataTable
                } else {
                    show_toast('Error!', response.message, 'error');
                }
            });
        }
    </script>
    
@endsection
