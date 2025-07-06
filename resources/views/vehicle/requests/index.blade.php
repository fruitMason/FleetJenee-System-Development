@extends('layouts.master')
@section('page_title', 'Car Request')
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
                        <h3 class="page-title">Car Request</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Fleet</a></li>
                            <li class="breadcrumb-item active">Car Request</li>
                        </ul>
                    </div>
                    <div class="col-auto float-end ms-auto">
                        <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#request_car_modal"><i
                                class="fa fa-plus"></i> Request New</a>
                    </div>
                </div>
            </div>

            <div class="row filter-row">
                <form class="row mb-5 filterForm" onsubmit="return false;">

                    <div class="col-sm-6 col-md-4">
                        <div class="form-group form-focus select-focus">
                            <select class="select floating" name="user_id" id="user_id">
                                <option>-- Select User --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->full_name() }}</option>
                                @endforeach
                            </select>
                            <label class="focus-label">User</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div class="form-group form-focus select-focus">
                            <select class="select floating" name="status" id="status">
                                <option>-- Select Status --</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                            <label class="focus-label">Status</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <button type="button" id="btnFilter" class="btn btn-success w-100"> Filter </button>
                    </div>
                </form>
            </div>

            <div class="row" id="card_content">
                <div class="col-md-12">
                    <div class="table-responsive">
                        {!! $dataTable->table(['class' => 'table table-striped custom-table']) !!}
                    </div>
                </div>
            </div>

        </div>

        @include('modal.request_car')
        @include('modal.approve_car_request')
    </div>

@endsection

@section('js')
    {!! $dataTable->scripts() !!}

    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        modal_select_search($('.create_select_search'), $('#form_create'));

        $(document).ready(function() {
            $('#dataTableBuilder')
                .on('preXhr.dt', function(e, settings, data) {
                    data.user_id = $('#user_id option:selected').val();
                    data.status = $('#status option:selected').val();
                });

            $('#btnFilter').on('click', function() {
                $('#dataTableBuilder').DataTable().ajax.reload();
                return false;
            });
        });

        // function approveNotify(id) {
        //     swal({
        //         title: "<small>Reason for approving this car request?</small>",
        //         input: "textarea",
        //         showCancelButton: true,
        //         confirmButtonColor: "#3858f9",
        //         confirmButtonText: "Proceed!",
        //         cancelButtonText: "No, cancel!",
        //         closeOnConfirm: false,
        //         closeOnCancel: true,
        //         showLoaderOnConfirm: true,
        //         inputPlaceholder: "type in a reason...",
        //         inputValidator: function(reason) { // validates your input
        //             return new Promise(function(resolve, reject) {
        //                 swal.close();
        //                 approve(id, reason);
        //             });
        //         }
        //     });
        // }

        function approveNotify(id, user_id, reason, requester) {
            console.log('reason--', reason, "requeted -", requester);

            waitme('card_content');
            setTimeout(function() {
                hidewaitme('card_content');
                $('#approve_car_modal .modal-title').html('Approving Car Request');
                $('#approve_car_request_id').val(id);
                $('#approve_car_user_id').val(user_id);
                $('#requester').val(requester);
                $('#reason_for_request').val(reason);
                $('#approve_car_modal').modal('show');
            }, 500);
        }


        function approve(id, reason) {
            ajax('{{ route('fleet.vehicle.request.approve') }}', {
                id: id,
                reason: reason,
                _token: _token
            }, 'card_content', function(response) {
                if (response.code === 200) {
                    show_toast('Success!', response.message, 'success');
                    // refreshDataTable();
                    refreshPage();
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
                inputPlaceholder: "type in a reason...",
                inputValidator: function(reason) { // validates your input
                    return new Promise(function(resolve, reject) {
                        swal.close();
                        reject_(id, reason);
                    });
                }
            });
        }


        function reject_(id, reason) {
            ajax('{{ route('fleet.vehicle.request.reject') }}', {
                id: id,
                reason: reason,
                _token: _token
            }, 'card_content', function(response) {
                if (response.code === 200) {
                    show_toast('Success!', response.message, 'success');
                    refreshPage();
                } else {
                    show_toast('Error!', response.message, 'error');
                }
            });
        }
    </script>

@endsection
