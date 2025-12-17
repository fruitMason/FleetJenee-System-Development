@extends('layouts.master')
@section('page_title', 'Car Requests')
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
                        <h3 class="page-title">Car Requests</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Driver</a></li>
                            <li class="breadcrumb-item active">Car Requests</li>
                        </ul>
                    </div>
                    <div class="col-auto float-end ms-auto">
                        <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#request_car_modal"><i
                                class="fa fa-plus"></i> Request New</a>
                    </div>
                </div>
            </div>






            <div class="row" id="card_content">
                <div class="col-md-12">
                    <section class="panel panel-default">
                        <div class="card mg-b-20" id="card_content">

                            <div class="card-body">


                                <div class="row filter-row">

                                    <div class="col-sm-6 col-md-3">
                                        <div class="form-group form-focus select-focus">
                                            <select class="select floating" id="statusGroupFilter">
                                                <option value="">-- Trip Status --</option>
                                                <option value="pending">New Trip</option>
                                                <option value="started">Started</option>
                                                <option value="ended">Completed</option>
                                            </select>
                                            <label class="focus-label">Status</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-3">
                                        <button type="button" id="btnStatusFilter" class="btn btn-success w-100">
                                            Filter </button>
                                    </div>
                                </div>


                                <div class="table-responsive">
                                    {!! $dataTable->table(['class' => 'table table-striped custom-table']) !!}
                                </div>

                            </div>
                        </div>
                    </section>
                </div>


            </div>

            @include('modal.driver.request_car')
        </div>

    @endsection

    @section('js')
        {!! $dataTable->scripts() !!}

        <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>

        <script>
            $(document).ready(function() {

                $('#btnStatusFilter').on('click', function() {
                    var filterValue = $('#statusGroupFilter').val();
                    console.log(filterValue);


                    $('#dataTableBuilder').DataTable().ajax.url(
                        '{{ route('driver.vehicle.request') }}?filter=' + filterValue).load();
                });
            });
        </script>

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
