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
                        <h3 class="page-title">Odometer Setting</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item active">Default Value</li>
                        </ul>
                    </div>
                    {{-- <div class="col-auto float-end ms-auto">
                        <a href="{{ route('finance.requests.home') }}" class="btn add-btn"><i class="fa fa-arrow-left"></i>
                            Back
                            to List</a>
                    </div> --}}
                </div>
            </div>




            <section class="panel panel-default">
                <div class="card mg-b-20" id="card_content">
                    <div class="card-body">
                        <div class="card-title">Value Detail</div>


                        <form method="post" action="{{ route('settings.odometer.update') }}"
                            onsubmit="return SubmitDelete(this,'Update Default Odometer');" class="confirmationForm"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- First Row - 3 equal columns -->
                            <div class="row mb-3">
                                <!-- Input 2 -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group form-focus select-focus">

                                            <label for="descriptiont" class="d-block mb-2">
                                                Default Value<span class="text-danger">*</span>
                                            </label>
                                            <input type="number" name="value" id="value" step="0.1"
                                                class="form-control" required value="{{ old('value',$odo->value) }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Input 3 -->

                            </div>
<br>



                            <div class="mb-3 row">
                                <div class="col-md-12 ">
                                    <button class="btn btn-primary submit-btn" type="submit">Submit</button>
                                </div>
                            </div>

                        </form>

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

            // $('.user-search2').select2();
            // $('.car-search2').select2();

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
