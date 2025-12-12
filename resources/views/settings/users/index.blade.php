@extends('layouts.master')
@section('page_title', 'Users')
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
                        <h3 class="page-title">@if(request()->get('type') == 'driver') Drivers and License Information @else Users @endif</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Settings</a></li>
                            <li class="breadcrumb-item active">Users</li>
                        </ul>
                    </div>
                    <div class="col-auto float-end ms-auto">
                        <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_user_modal"><i class="fa fa-plus"></i> Add New</a>
                        <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_bulk_user_modal"><i class="fa fa-plus"></i> Bulk</a>
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

        @include('modal.add_user')
        @include('modal.add_bulk_user')
        @include('modal.edit_user')

    </div>

@endsection

@section('js')
    {!! $dataTable->scripts() !!}

    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>

    <script>

         // Function to toggle driver type dropdown visibility
         function toggleDriverType() {
            var userType = document.getElementById('user_type').value;
            var driverTypeContainer = document.getElementById('driver_type_container');
            driverTypeContainer.style.display = (userType === 'DRIVER') ? 'block' : 'none';
        }

        // Reset the modal when it's shown
        $('#add_user_modal').on('show.bs.modal', function () {
            // Reset form fields
            $(this).find('form')[0].reset();
            toggleDriverType(); // Reset driver type visibility
        });
        
        modal_select_search($('.create_select_search'), $('#form_create'));
        modal_select_search($('.edit_select_search'), $('#form_edit'));

        function editNotify(id) {
            ajax('{{route('settings.users.get')}}', {
                id: id,
                _token: _token
            }, 'card_content', function (response) {
                let data = response.message;
                $('#edit_modal .modal-title').html('Updating: ' + data.first_name + ' ' + data.last_name);
                $('#edit_id').val(data.id);
                $('#edit_first_name').val(data.first_name);
                $('#edit_middle_name').val(data.middle_name);
                $('#edit_last_name').val(data.last_name);
                $('#edit_email').val(data.email);
                $('#edit_mobile').val(data.mobile);
                $('#edit_role').val(data.roles[0].id).change();
                $('#edit_department_id').val(data.department_id).change();
                $('#edit_type').val(data.type.toUpperCase()).change();

                 // Set driver type and toggle visibility
                $('#edit_driver_type').val(data.driver_type).change();
                toggleDriverType(); // Call to check visibility based on type

                $('#edit_license_class').val(data.license_class);
                $('#edit_license_number').val(data.license_number);
                $('#edit_license_expiry').val(data.license_expiry);
                $('#edit_vendor_id').val(data.vendor_id).change();
                $('#edit_modal').modal('show');
            });
        }

        $('#btnEdit').on('click', function () {
            let id = $('#edit_id').val();
            let first_name = $("#edit_first_name").val();
            let middle_name = $("#edit_middle_name").val();
            let last_name = $("#edit_last_name").val();
            let email = $("#edit_email").val();
            let mobile = $("#edit_mobile").val();
            let role = $("#edit_role option:selected").val();
            let department_id = $("#edit_department_id option:selected").val();
            let type = $("#edit_type option:selected").val();

            // Use type from the dropdown instead of data
            let driver_type = "";
            if (type === 'DRIVER') {
                $('#driver_type_container').show();
                driver_type = $("#edit_driver_type option:selected").val();
            } else {
                $('#driver_type_container').hide();
                driver_type = ''; // Reset value if not a driver
            }

            let license_class = $("#edit_license_class").val();
            let license_number = $("#edit_license_number").val();
            let license_expiry = $("#edit_license_expiry").val();
            let vendor_id = $("#edit_vendor_id option:selected").val();

            // Validate fields
            if(first_name === '' || last_name === '' || email === '' || role === undefined || role === '' || department_id === undefined || department_id === '' || type === '') {
                show_toast('Caution!', 'One or more required fields are empty', 'warning');
                return;
            }

            // AJAX call to update
            ajax('{{route('settings.users.update')}}', {
                id: id,
                first_name: first_name,
                middle_name: middle_name,
                last_name: last_name,
                email: email,
                mobile: mobile,
                role: role,
                department_id: department_id,
                type: type,
                driver_type: driver_type,
                license_class: license_class,
                license_number: license_number,
                license_expiry: license_expiry,
                vendor_id: vendor_id,
                _token: _token
            }, 'edit_modal_content', function (response) {
                if(response.code === 200) {
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
        });


        function deleteNotify(id) {
            swal({
                title: "<small>Reason for deleting this user?</small>",
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
                        _delete(id, reason);
                    });
                }
            });
        }

        function _delete(id, reason) {
            ajax('{{route('settings.users.delete')}}', {
                id: id,
                reason: reason,
                _token: _token
            }, 'card_content', function (response) {
                show_toast('Success!', response.message, 'success');
                refreshDataTable();
            });
        }

        
    </script>

@endsection
