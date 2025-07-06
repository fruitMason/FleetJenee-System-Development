@extends('layouts.master')

@section('page_title', 'View Maintenance Work Order')

{{-- @section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">
@endsection --}}

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="col-md-12">
                @include('includes.error')
            </div>

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Auto Part Purchase Request</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"> <a href="{{ route('finance.requests.home') }}">Requests</a> </li>
                            <li class="breadcrumb-item active">Auto Parts Purchases</li>
                        </ul>
                    </div>
                    <div class="col-auto float-end ms-auto">
                        <a href="{{ route('inventory.create') }}" class="btn add-btn"><i class="fas fa-tools"></i>
                            New Request - Stock In
                        </a>

                        <a href="{{ route('auto.parts.index') }}" class="btn add-btn"><i class="fas fa-tools"></i>
                            Auto Parts</a>
                    </div>
                </div>
            </div>




            <section class="panel panel-default">
                <div class="card mg-b-20" id="card_content">
                    <div class="card-body">
                        <div class="card-title">Request Detail </div>

                        <div>Under Construction!</div>
                        
                        {{-- <form method="post" action="{{ route('finance.requests.general.store') }}"
                            onsubmit="return SubmitDelete(this,'Save General Request');" class="confirmationForm"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- First Row - 3 equal columns -->
                            <div class="row">
                                <!-- Input 1 -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-focus select-focus">
                                            <label for="descriptiont" class="d-block mb-2">
                                                Request Date <span class="text-danger">*</span>
                                            </label>

                                            <input type="date" name="request_date" class="form-control"
                                                value="{{ old('request_date') }}" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Input 2 -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group form-focus select-focus">

                                            <label for="descriptiont" class="d-block mb-2">
                                                Request Amount <span class="text-danger">*</span>
                                            </label>
                                            <input type="number" name="amount" id="amount" step="0.1"
                                                class="form-control" required value="{{ old('amount') }}">
                                        </div>
                                    </div>
                                </div>

                             
                            </div>

                            <!-- Second Row - 1 input taking 2/3 width -->
                            <div class="row mb-3">
                                <!-- Large Input (2/3 width) -->
                               

                            </div>

                            <!-- 3rd row -->

                            <div class="mb-3 row">
                                <div class="col-md-8">
                                    <label for="descriptiont" class="d-block mb-2">
                                        Naration
                                    </label>
                                    <textarea class="form-control" name="description" id="description" rows="5">{{ old('naration') }}</textarea>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-md-12 ">
                                    <button class="btn btn-primary submit-btn" type="submit">Submit</button>
                                </div>
                            </div>

                        </form> --}}

                    </div>
                </div>

            </section>




        </div>
    </div>
@endsection


@section('js')


    <script src="{{ asset('assets/js/select2.min.js') }}"></script>

    <script>
        // In your Javascript (external .js resource or <script> tag)
        $(document).ready(function() {
            console.log('document ready');



            const carSelect = document.getElementById('carSelect');
            // console.log(carSelect);

            $('#carSelect').on('change', function() {
                var selectedValue = $(this).val();
                var selectedText = $(this).find('option:selected').text();

                console.log('Selected Car ID:', selectedValue);
                // console.log('Selected Car Text:', selectedText); 
                if (selectedText.toLowerCase().includes('pool')) {

                    $('#id_for_user_id').val('1');
                    $('#id_for_user_id').prop('disabled', true);
                } else {

                    $('#id_for_user_id').prop('disabled', false);
                    $('#id_for_user_id').val('');
                }

            });
        });
    </script>


@endsection
