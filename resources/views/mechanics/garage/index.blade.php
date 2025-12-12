@extends('layouts.master')
@section('page_title', 'Garage')
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
                        <h3 class="page-title">Garage</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Mechanic</a></li>
                            <li class="breadcrumb-item active">Garage</li>
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

        @include('modal.mechanic.garage.add_confirm_receipt')
        @include('modal.mechanic.garage.add_diagnosis')
        @include('modal.mechanic.garage.add_completed') 

    </div>

@endsection

@section('js')
    {!! $dataTable->scripts() !!}

    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>

    <script>
    

        // show modal of work details
       

        function confirmReceiptNotify(id, car_id) {
            waitme('card_content');
            setTimeout(function() {
                hidewaitme('card_content');
                $('#car').val(car_id).change();
                $('#car_id').val(car_id);
                $('#id').val(id);
                $('#add_confirm_receipt_modal').modal('show');
            }, 500);
        }

        function uploadDiagnosisNotify(id, car_id) {
            waitme('card_content');
            setTimeout(function() {
                hidewaitme('card_content');
                $('#car_diagnosis').val(car_id).change();
                $('#car_id_diagnosis').val(car_id);
                $('#id_diagnosis').val(id);
                $('#add_diagnosis_modal').modal('show');
            }, 500);
        }

        function confirmCompletedNotify(id, car_id) {
            waitme('card_content');
            setTimeout(function() {
                hidewaitme('card_content');
                $('#car_completed').val(car_id).change();
                $('#car_id_completed').val(car_id);
                $('#id_completed').val(id);
                $('#add_completed_modal').modal('show');
            }, 500);
        }
    </script>
@endsection
