@extends('layouts.master')
@section('page_title', 'Maintenance History')
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
                        <h3 class="page-title">Work Order</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Fleet</a></li>
                            <li class="breadcrumb-item active">Maintenance</li>
                        </ul>
                    </div>
                    <div class="col-auto float-end ms-auto">
                        <a href="#" class="btn add-btn" onclick="startWorkOrder()"><i class="fa fa-plus"></i> Add
                            New</a>
                    </div>
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

        @include('modal.add_maintenance')

    </div>

@endsection

@section('js')
    {!! $dataTable->scripts() !!}

    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        function maintainNotify(id) {
            waitme('card_content');
            setTimeout(function() {
                hidewaitme('card_content');
                $('#car_id').val(id).change();
                $('#add_maintenance_modal').modal('show');
            }, 500);
        }

        function startWorkOrder() {
            waitme('card_content');
            setTimeout(function() {
                hidewaitme('card_content');
                $('#add_maintenance_modal').modal('show');
                $('#car_id').removeAttr('disabled');
            }, 500);
        }

        function maintain(id, comment) {
            ajax('{{ route('fleet.vehicle.registration.maintain') }}', {
                id: id,
                comment: comment,
                _token: _token
            }, 'card_content', function(response) {
                if (response.code === 200) {
                    show_toast('Success!', response.message, 'success');
                    refreshDataTable();
                    // refreshPage();
                } else {
                    show_toast('Error!', response.message, 'error');
                }
            });
        }

        $(document).ready(function() {
            $('.maintenanceForm').submit(function(e) {
                e.preventDefault();

                let car_id = $('#car_id option:selected').val();
                let type = $('#type option:selected').val();
                let mechanic_id = $('#mechanic_id option:selected').val();
                let comment = $('#comment').val();
                let start_date = $('#start_date').val();
                let end_date = $('#end_date').val();

                if (car_id === '' || type === '' || mechanic_id === '' || comment === '' || start_date ===
                    '') {
                    show_toast('Error!', 'One or more field is required', 'error');
                    return;
                }

                ajax('{{ route('fleet.vehicle.registration.maintain') }}', {
                    car_id: car_id,
                    type: type,
                    mechanic_id: mechanic_id,
                    comment: comment,
                    start_date: start_date,
                    end_date: end_date,
                    _token: _token
                }, 'modal_body', function(response) {
                    if (response.code === 200) {
                        show_toast('Success!', response.message, 'success');

                        if (response.url == 'NO') {
                            setTimeout(function() {//wait for 2secs and refresh datatable
                                refreshDataTable();
                                //refreshPage();
                            }, 2000);
                        } else {
                            window.location = response.url
                        }

                    } else {
                        show_toast('Error!', response.message, 'error');
                    }
                });
            })
        })
    </script>
@endsection
