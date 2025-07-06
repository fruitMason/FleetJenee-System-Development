@extends('layouts.master')

@section('page_title', 'Finance Request')

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
                        <h3 class="page-title">General Finance Requests</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"> <a href="{{ route('finance.requests.home') }}">Requests</a> </li>
                            <li class="breadcrumb-item active">Create General Request</li>
                        </ul>
                    </div>
                    <div class="col-auto float-end ms-auto">
                        <a href="{{ route('finance.requests.home') }}" class="btn add-btn"><i class="fa fa-arrow-left"></i>
                            Back
                            to List</a>
                    </div>
                </div>
            </div>




            <section class="panel panel-default">
                <div class="card mg-b-20" id="card_content">
                    <div class="card-body">
                        <div class="card-title">Request Detail </div>


                        <form method="post" action="{{ route('finance.requests.general.store') }}"
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

                                <!-- Input 3 -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="descriptiont" class="d-block mb-2">
                                                Request Type<span class="text-danger">*</span>
                                            </label>


                                            <select class="select floating" name="payment_type" required
                                                onchange="copySelectedText(this)">
                                                <option value="">Select Request Type *</option>
                                                @foreach ($pay_type as $yn)
                                                    @if (old('payment_type') == $yn->id)
                                                        <option value="{{ $yn->id }}" selected>
                                                            {{ $yn->name }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $yn->id }}">
                                                            {{ $yn->name }}
                                                        </option>
                                                    @endif
                                                @endforeach


                                            </select>
                                            <script>
                                                function copySelectedText(selectElement) {
                                                    // Get the selected option text
                                                    const selectedText = selectElement.options[selectElement.selectedIndex].text;

                                                    // Copy to input field
                                                    document.getElementById('description').value = selectedText + " ";


                                                }
                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Second Row - 1 input taking 2/3 width -->
                            <div class="row">
                                <!-- Large Input (2/3 width) -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="car-select" class="d-block mb-2">
                                            <span class="font-weight-bold">Car</span>
                                            <span class="text-danger">*</span>
                                        </label>

                                        <select class="select floating car-search2 " id="carSelect" name="car_id" required
                                            style="width: 100%">
                                            <option value="">Select Car</option>
                                            @foreach ($cars as $car)
                                                @if (old('car_id') == $car->id)
                                                    <option value="{{ $car->id }}" selected>
                                                        {{ $car->model }} || {{ $car->car_number }} ||
                                                        {{ ucwords($car->car_group) }}
                                                    </option>
                                                @else
                                                    <option value="{{ $car->id }}">
                                                        {{ $car->model }} || {{ $car->car_number }} ||
                                                        {{ ucwords($car->car_group) }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

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
