@extends('layouts.master')
@section('page_title', 'Cars')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">

    <style>
        .file-upload {
            background-color: #ffffff;
            width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .file-upload-btn {
            width: 100%;
            margin: 0;
            color: #fff;
            background: #1FB264;
            border: none;
            padding: 10px;
            border-radius: 4px;
            border-bottom: 4px solid #15824B;
            transition: all .2s ease;
            outline: none;
            text-transform: uppercase;
            font-weight: 700;
        }

        .file-upload-btn:hover {
            background: #1AA059;
            color: #ffffff;
            transition: all .2s ease;
            cursor: pointer;
        }

        .file-upload-btn:active {
            border: 0;
            transition: all .2s ease;
        }

        .file-upload-content {
            display: none;
            text-align: center;
        }

        .file-upload-input {
            position: absolute;
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            outline: none;
            opacity: 0;
            cursor: pointer;
        }

        .image-upload-wrap {
            margin-top: 20px;
            border: 4px dashed #1FB264;
            position: relative;
        }

        .image-dropping,
        .image-upload-wrap:hover {
            background-color: #1FB264;
            border: 4px dashed #ffffff;
        }

        .image-title-wrap {
            padding: 0 15px 15px 15px;
            color: #222;
        }

        .drag-text {
            text-align: center;
        }

        .drag-text h3 {
            font-weight: 100;
            text-transform: uppercase;
            color: #15824B;
            padding: 60px 0;
        }

        .file-upload-image {
            max-height: 200px;
            max-width: 200px;
            margin: auto;
            padding: 20px;
        }

        .remove-image {
            width: 200px;
            margin: 0;
            color: #fff;
            background: #cd4535;
            border: none;
            padding: 10px;
            border-radius: 4px;
            border-bottom: 4px solid #b02818;
            transition: all .2s ease;
            outline: none;
            text-transform: uppercase;
            font-weight: 700;
        }

        .remove-image:hover {
            background: #c13b2a;
            color: #ffffff;
            transition: all .2s ease;
            cursor: pointer;
        }

        .remove-image:active {
            border: 0;
            transition: all .2s ease;
        }
    </style>
@endsection
@section('content')
    <div class="page-wrapper">

        <div class="content container-fluid">

            <div class="col-md-12">@include('includes.error')</div>

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Cars</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Fleet</a></li>
                            <li class="breadcrumb-item active">Cars</li>
                        </ul>
                    </div>
                    <div class="col-auto float-end ms-auto">
                        <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_car_modal"><i
                                class="fa fa-plus"></i> Add New</a>
                        <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_bulk_car_modal"><i
                                class="fa fa-plus"></i> Bulk</a>
                    </div>
                </div>
            </div>

            <div class="row filter-row" id="regionFilter">
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus select-focus">
                        <select class="select floating" id="carGroupFilter">
                            <option value="">-- Select Car Group --</option>
                            <option value="pool">Pool</option>
                            <option value="assigned">Assigned</option>
                        </select>
                        <label class="focus-label">Car Group</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <button type="button" id="btnGroupFilter" class="btn btn-success w-100">Filter</button>
                </div>
            </div>

            <div class="row filter-row" id="carAgeFilter">
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus select-focus">
                        <select class="select floating" id="car-age-filter">
                            <option value="">-- Select Car Age --</option>
                            <option value="older_than_3_years">Cars 3 Years Older</option>
                        </select>
                        <label class="focus-label">Car Age</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <button type="button" id="btnAgeFilter" class="btn btn-success w-100">Filter</button>
                </div>
            </div>

            <div class="row" id="card_content">
                <div class="col-md-12">
                    <div class="table-responsive">
                        {!! $dataTable->table(['class' => 'table table-striped custom-table', 'id' => 'dataTableBuilder']) !!}
                    </div>
                </div>
            </div>
        </div>

        @include('modal.add_maintenance')
        @include('modal.add_car')
        @include('modal.add_bulk_car')
        @include('modal.edit_car')

    </div>

@endsection

@section('js')
    {!! $dataTable->scripts() !!}

    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>

    <script>
        modal_select_search($('.create_select_search'), $('#form_create'));
        modal_select_search($('.edit_select_search'), $('#form_edit'));

        function editNotify(id) {
            ajax('{{ route('fleet.vehicle.registration.get') }}', {
                id: id,
                _token: _token
            }, 'card_content', function(response) {
                let data = response.message;
                $('#edit_modal .modal-title').html('Updating: ' + data.model);
                $('#edit_id').val(data.id);
                $('#edit_model').val(data.model);
                const year = data.year.slice(0, 10);
                console.log(year);
                $('#edit_year').val(data.year);
                $('#edit_body_style').val(data.body_style);
                $('#edit_trim_level').val(data.trim_level);
                $('#edit_color').val(data.color);
                $('#edit_car_number').val(data.car_number);
                console.log('car group',data.car_group);
                
                $('#edit_car_group').val(data.car_group);
                $('#edit_chassis').val(data.chassis);
                $('#edit_odometer').val(data.odometer);
                $('#edit_engine_capacity').val(data.engine_capacity);
                $('#edit_fuel_type').val(data.fuel_type).change();
                $('#edit_user_id').val(data.user_id).change();
                console.log('road worth', data.road_worthy_start_date);
                const road_start = data.road_worthy_start_date ? data.road_worthy_start_date.slice(0, 10) : '';
                const road_end = data.road_worthy_expiry_date ? data.road_worthy_expiry_date.slice(0, 10) : '';
                $('#edit_road_worthy_start_date').val(road_start); //data.road_worthy_start_date
                $('#edit_road_worthy_expiry_date').val(road_end);
                $('#edit_status').val(data.status).change();
                $('#edit_comment').val(data.comment);
                const insu_start = data.insurance_start_date ? data.insurance_start_date.slice(0, 10) : '';
                const insu_end = data.insurance_expiry ?  data.insurance_expiry.slice(0, 10) : '';
                $('#edit_insurance_start_date').val(insu_start);
                $('#edit_insurance_expiry').val(insu_end);

                $('#edit_modal').modal('show');
            });
        }

        console.log('road worthy', );


        $('#btnEdit').on('click', function() {
            let id = $('#edit_id').val();
            let model = $("#edit_model").val();
            let year = $("#edit_year").val();
            let body_style = $("#edit_body_style").val();
            let trim_level = $("#edit_trim_level").val();
            let car_group = $("#edit_car_group").val();
            let color = $("#edit_color").val();
            let car_number = $("#edit_car_number").val();
            let chassis = $("#edit_chassis").val();
            let odometer = $("#edit_odometer").val();
            let engine_capacity = $("#edit_engine_capacity").val();
            let fuel_type = $("#edit_fuel_type option:selected").val();
            let user_id = $("#edit_user_id option:selected").val();
            let road_worthy_start_date = $("#edit_road_worthy_start_date").val();
            let road_worthy_expiry_date = $("#edit_road_worthy_expiry_date").val();
            let status = $("#edit_status option:selected").val();
            let comment = $("#edit_comment").val();
            let insurance_start_date = $("#edit_insurance_start_date").val();
            let insurance_expiry = $("#edit_insurance_expiry").val();

            if (model === '' || year === '' || car_number === '' || odometer === '' || road_worthy_start_date ===
                '' || road_worthy_expiry_date === '') {
                show_toast('Caution!', 'One or more required field is empty', 'warning');
                return;
            }

            ajax('{{ route('fleet.vehicle.registration.update') }}', {
                id: id,
                model: model,
                year: year,
                body_style: body_style,
                trim_level: trim_level,
                color: color,
                car_number: car_number,
                car_group: car_group,
                chassis: chassis,
                odometer: odometer,
                engine_capacity: engine_capacity,
                fuel_type: fuel_type,
                user_id: user_id,
                road_worthy_start_date: road_worthy_start_date,
                road_worthy_expiry_date: road_worthy_expiry_date,
                status: status,
                comment: comment,
                insurance_start_date: insurance_start_date,
                insurance_expiry: insurance_expiry,
                _token: _token
            }, 'edit_modal_content', function(response) {
                if (response.code === 200) {
                    $('#edit_modal').modal('hide');
                    show_toast('Success!', response.message, 'success');
                    waitme("card_content");
                    setTimeout(function() {
                        hidewaitme("card_content");
                        refreshDataTable();
                    }, 200);
                } else {
                    show_toast('Caution!', response.message, 'warning');
                }
            });
        })

        function deleteNotify(id) {
            swal({
                title: "<small>Reason for deleting this car?</small>",
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
                        // _delete(id, reason);
                    });
                }
            });
        }

        function _delete(id, reason) {
            ajax('{{ route('fleet.vehicle.registration.delete') }}', {
                id: id,
                reason: reason,
                _token: _token
            }, 'card_content', function(response) {
                show_toast('Success!', response.message, 'success');
                refreshDataTable();
            });
        }

        function maintainNotify(id) {
            waitme('card_content');
            setTimeout(function() {
                hidewaitme('card_content');
                $('#car_id').val(id).change();
                $('#add_maintenance_modal').modal('show');
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
                        // refreshDataTable();
                        refreshPage();
                    } else {
                        show_toast('Error!', response.message, 'error');
                    }
                });
            })

        })
    </script>



    <script>
        // Group filter
        $('#btnGroupFilter').on('click', function() {
            var selectedGroup = $('#carGroupFilter').val(); // Get the selected value
            var dataTable = $('#dataTableBuilder').DataTable();

            // Update the DataTable's AJAX URL with the selected car group
            dataTable.ajax.url('{{ route('fleet.vehicle.registration') }}?carGroup=' + selectedGroup).load();
        });

        // Car age filter
        $('#btnAgeFilter').on('click', function() {
            var selectedAge = $('#car-age-filter').val(); // Get the selected value
            var dataTable = $('#dataTableBuilder').DataTable();

            // Update the DataTable's AJAX URL based on the selected age filter
            if (selectedAge === 'older_than_3_years') {
                dataTable.ajax.url('{{ route('fleet.vehicle.registration') }}?age=older_than_3_years').load();
            } else {
                // Reset to default if no filter is selected
                dataTable.ajax.url('{{ route('fleet.vehicle.registration') }}').load();
            }
        });
    </script>
    <script>
        function archiveNotify(carId) {
            if (confirm("Are you sure you want to archive this car?")) {
                $.ajax({
                    url: '{{ route('cars.archive') }}',
                    type: 'POST',
                    data: {
                        id: carId,
                        _token: '{{ csrf_token() }}' // Include CSRF token
                    },
                    success: function(response) {
                        // Handle success, refresh DataTable or show a success message
                        alert(response.message);
                        location.reload();
                    },
                    error: function(response) {
                        // Handle error
                        alert('Error archiving car.');
                    }
                });
            }
        }
    </script>



@endsection
