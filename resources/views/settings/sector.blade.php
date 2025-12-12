@extends('layouts.master')
@section('page_title', 'Zones')
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
                        <h3 class="page-title">Zones</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Settings</a></li>
                            <li class="breadcrumb-item active">Zones</li>
                        </ul>
                    </div>
                    <div class="col-auto float-end ms-auto">
                        <a href="#" class="btn add-btn" data-bs-toggle="modal" data-bs-target="#add_sector_modal"><i class="fa fa-plus"></i> Add New</a>
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

        @include('modal.add_sector')
        @include('modal.edit_sector')

    </div>

@endsection

@section('js')
    {!! $dataTable->scripts() !!}

    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>

    <script>

        function editNotify(id){
            ajax('{{route('settings.sectors.get')}}', {
                id: id,
                _token: _token
            }, 'card_content', function (response) {
                let data = response.message;
                $('#edit_modal .modal-title').html('Updating: ' + data.name);
                $('#edit_id').val(data.id);
                $('#edit_name').val(data.name);
                $('#edit_description').val(data.description);
                $('#edit_modal').modal('show');
            });
        }

        $('#btnEdit').on('click', function () {
            let id = $('#edit_id').val();
            let name = $("#edit_name").val();
            let description = $("#edit_description").val();

            if(name === '') {
                show_toast('Caution!', 'Please Enter Name', 'warning');
                return;
            }

            ajax('{{route('settings.sectors.update')}}', {
                id: id,
                name: name,
                description: description,
                _token: _token
            }, 'edit_modal_content', function (response) {
                $('#edit_modal').modal('hide');
                show_toast('Success!', response.message, 'success');
                waitme("card_content");
                setTimeout(function() {
                    hidewaitme("card_content");
                    refreshDataTable();
                }, 200);
            });
        })

        function deleteNotify(id) {
            swal({
                title: "<small>Reason for deleting this zone?</small>",
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
            ajax('{{route('settings.sectors.delete')}}', {
                id: id,
                reason: reason,
                _token: _token
            }, 'card_content', function (response) {
                show_toast('Success!', response.message, 'success');
                refreshDataTable();
            });
        }
    </script>
    <script>
        function archiveNotify(sectorId) {
            if (confirm("Are you sure you want to archive this zone?")) {
                $.ajax({
                    url: '{{ route("sectors.archive") }}',
                    type: 'POST',
                    data: {
                        id: sectorId,
                        _token: '{{ csrf_token() }}' // Include CSRF token
                    },
                    success: function(response) {
                        // Handle success, refresh DataTable or show a success message
                        alert(response.message);
                        location.reload();
                    },
                    error: function(response) {
                        // Handle error
                        alert('Error archiving zone.');
                    }
                });
            }
        }
    </script>
@endsection
