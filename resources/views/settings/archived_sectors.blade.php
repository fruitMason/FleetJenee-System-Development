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
                        <h3 class="page-title">Archived Zones</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Settings</a></li>
                            <li class="breadcrumb-item active">Archived Zones</li>
                        </ul>
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

    </div>

@endsection

@section('js')
    {!! $dataTable->scripts() !!}

    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>

    <script>

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
        function unarchiveNotify(sectorId) {
            if (confirm("Are you sure you want to unarchive this zone?")) {
                $.ajax({
                    url: '{{ route("sectors.unarchive") }}', // Ensure this route is set up
                    type: 'POST',
                    data: {
                        id: sectorId,
                        _token: '{{ csrf_token() }}' // Include CSRF token
                    },
                    success: function(response) {
                        alert(response.message);
                        $('#dataTableBuilder').DataTable().ajax.reload(); // Refresh DataTable
                    },
                    error: function(response) {
                        alert('Error unarchiving zone.');
                    }
                });
            }
        }
    </script>

@endsection
