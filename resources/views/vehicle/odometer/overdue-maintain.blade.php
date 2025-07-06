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
                        <h3 class="page-title">Generate Maintenance For Overdue Odometer</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"> <a href="{{ route('fleet.vehicle.odometer.overdue') }}">Overdue
                                    Odometer</a> </li>
                            <li class="breadcrumb-item active">Create Maintenance Request</li>
                        </ul>
                    </div>
                    <div class="col-auto float-end ms-auto">
                        <a href="{{ route('fleet.vehicle.odometer.overdue') }}" class="btn add-btn"><i
                                class="fa fa-arrow-left"></i>
                            Back
                            to List</a>
                    </div>
                </div>
            </div>




            <section class="panel panel-default">
                <div class="card mg-b-20" id="card_content">
                    <div class="card-body">
                        <div class="card-title">Request Detail </div>

                        <form method="post" class="maintenanceForm"
                            onsubmit="return SubmitDelete(this,'Submit Maintenance Order');" 
                            action="{{ route('fleet.vehicle.registration.maintain') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="col-form-label">Car <span class="text-danger">*</span></label>
                                                <input type="hidden" name='car_id' value="{{ $car->id }}">
                                                <input type="hidden" name='overdue' value="Overdue">
                                                <input class="form-control" type="text" name="car" id="car"
                                                    required value="{{ $car->car_features() }} " disabled>

                                                </input>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="col-form-label">Type <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control select" name="type" id="type" required>
                                                    <option>-- select maintenance type --</option>
                                                    <option value="breakdown">Break Down</option>
                                                    <option value="normal" selected>Normal Routine</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="col-form-label">Mechanic <span
                                                        class="text-danger">*</span></label>
                                                <select class="form-control select" name="mechanic_id" id="mechanic_id"
                                                    required>
                                                    <option>-- select mechanic --</option>
                                                    @foreach ($mechanics as $mechanic)
                                                        <option value="{{ $mechanic->id }}">{{ $mechanic->full_name() }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3"> <label class="col-form-label">Start Date <span
                                                        class="text-danger">*</span></label>
                                                <input type="date" name="start_date" id="start_date" class="form-control"
                                                    value="">
                                            </div>

                                            <div class="col-md-3"> <label class="col-form-label">End Date </label>
                                                <input type="date" name="end_date" id="end_date" class="form-control"
                                                    value="">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">Comment <span class="text-danger">*</span>
                                                <textarea class="form-control" name="comment" id="comment" rows="3">Overdue Odometer. Overdue level: {{ number_format($car->odometer_level) }}, Current Odometer: {{ number_format($car->odometer) }}</textarea>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="form-group">
                                        <div class="submit-section">
                                            <button class="btn btn-primary submit-btn">Submit</button>
                                        </div>
                                    </div>
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
