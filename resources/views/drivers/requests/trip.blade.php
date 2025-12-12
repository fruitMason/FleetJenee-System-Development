@extends('layouts.master')
@section('page_title', 'New Auto Part')
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
                        <h3 class="page-title">Car Request - Trip</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('driver.vehicle.request') }}">Car Requests</a>
                            </li>
                            <li class="breadcrumb-item active">New Trip</li>
                        </ul>
                    </div>

                    <div class="col-auto float-end ms-auto ">
                        <a href="{{ route('driver.vehicle.request') }}" class="btn add-btn"><i class="fa fa-arrow-left"></i>
                            Back To List</a>
                    </div>
                </div>
            </div>






            <section class="panel panel-default">
                <div class="card mg-b-20" id="card_content">
                    <div class="card-body">
                        <div class="card-title">
                            @if ($carRequest->trip_status == 'pending')
                                New Trip
                            @elseif ($carRequest->trip_status == 'started')
                                <span class="text-secondary">Trip : Onroute...</span>
                            @else
                                <span class="text-primary">Trip Ended</span>
                            @endif

                        </div>
                        {{-- {{ $carRequest }} --}}
                        @php
                            $loc = '';
                            if ($location) {
                                $loc = $location->regionName . ', ' . $location->cityName;
                            }

                        @endphp

                        <div class="form-group">
                            <div class="row mb-3">

                                <div class="col-md-6">
                                    <label class="col-form-label">Assigned Car </label>
                                    <input type="text" class="form-control" id="total"
                                        value="{{ $carRequest->car->car_features() }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="col-form-label">Request Reason:
                                    </label>
                                    <input type="text" class="form-control" id="invoice"
                                        value="{{ $carRequest->request_reason }}" disabled>
                                </div>
                                {{-- <div class="col-md-4">
                                    <label class="col-form-label">Auth Comment:
                                    </label>
                                    <input type="text" class="form-control" id="invoice"
                                        value="{{ $carRequest->auth_comment }}" disabled>
                                </div> --}}

                            </div>

                            @if ($carRequest->trip_status == 'ended')
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="col-form-label">Start Time </label>
                                        <input type="text" class="form-control" id="total"
                                            value="{{ $carRequest->start_time }}" disabled>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="col-form-label">End Time
                                        </label>
                                        <input type="text" class="form-control" id="invoice"
                                            value="{{ $carRequest->end_time }}" disabled>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="col-form-label">Start Odometer </label>
                                        <input type="text" class="form-control" id="total"
                                            value="{{ $carRequest->start_odometer }}" disabled>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="col-form-label">End Odometer
                                        </label>
                                        <input type="text" class="form-control" id="invoice"
                                            value="{{ $carRequest->end_odometer }}" disabled>
                                    </div>



                                    <div class="col-md-6">
                                        <label class="col-form-label">Start Location </label>
                                        <input type="text" class="form-control" id="total"
                                            value="{{ $carRequest->start_location }}" disabled>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="col-form-label">End Location
                                        </label>
                                        <input type="text" class="form-control" id="invoice"
                                            value="{{ $carRequest->end_location }}" disabled>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="col-form-label">Start Comment:
                                        </label>
                                        <input type="text" class="form-control" id="invoice"
                                            value="{{ $carRequest->start_comment }}" disabled>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="col-form-label">End Comment:
                                        </label>
                                        <input type="text" class="form-control" id="invoice"
                                            value="{{ $carRequest->end_comment }}" disabled>
                                    </div>

                                </div>
                            @endif
                        </div>

                        {{-- pending -- START TRIP --}}
                        @if ($carRequest->trip_status == 'pending')
                            <form method="post" action="{{ route('driver.vehicle.trip.start', $carRequest->id) }}"
                                onsubmit="return SubmitDelete(this,'Start New Trip');">
                                @csrf
                                <button class="btn btn-success text-xl form-control" style="height: 80px">START TRIP NOW
                                    !</button>

                                <!-- First Row - 3 equal columns -->
                                <div class="row mb-3">
                                    <!-- Input 1 -->
                                    <div class="col-md-4">
                                        <label class="col-form-label">Enter Your Odometer<span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="odometer"
                                            value="{{ old('odometer', $carRequest->car->odometer) }}"
                                            placeholder="Enter Auto Part" step="0.1" required>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="col-form-label">Your Current Location<span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="location" id="location" class="form-control"
                                            required value="{{ old('location', $loc) }}" required
                                            placeholder="Enter Location">
                                    </div>


                                </div>


                                <div class="mb-3 row">
                                    <div class="col-md-12">
                                        <label for="descriptiont" class="d-block mb-2">
                                            Any Other Comment
                                        </label>
                                        <textarea class="form-control" name="comment" id="comment" rows="5">{{ old('comment') }}</textarea>
                                    </div>
                                </div>

                                {{-- <div class="mb-3 row">
                                <div class="col-md-12 ">
                                    <button class="btn btn-primary submit-btn" type="submit">Submit</button>
                                </div>
                            </div> --}}

                            </form>
                        @endif

                        {{-- tarted -- END TRIP --}}

                        @if ($carRequest->trip_status == 'started')
                            <form method="post" action="{{ route('driver.vehicle.trip.end', $carRequest->id) }}"
                                onsubmit="return SubmitDelete(this,'End Trip');">
                                @csrf


                                <!-- First Row - 3 equal columns -->
                                <div class="row mb-3">
                                    <!-- Input 1 -->
                                    <div class="col-md-4">
                                        <label class="col-form-label">Enter Your Odometer<span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="odometer"
                                            value="{{ old('odometer', $carRequest->car->odometer) }}"
                                            placeholder="Enter Auto Part" step="0.1" required>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="col-form-label">Your Current Location<span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="location" id="location" class="form-control"
                                            required value="{{ old('location', $loc) }}" required
                                            placeholder="Enter Location">
                                    </div>


                                </div>


                                <div class="mb-3 row">
                                    <div class="col-md-12">
                                        <label for="descriptiont" class="d-block mb-2">
                                            Any Other Comment
                                        </label>
                                        <textarea class="form-control" name="comment" id="comment" rows="5">{{ old('comment') }}</textarea>
                                    </div>
                                </div>

                                <button class="btn btn-danger text-xl form-control" style="height: 80px">END TRIP NOW
                                    !</button>


                            </form>
                        @endif


                    </div>
                </div>

            </section>


        </div>

    </div>

@endsection

@section('js')
    <script>
        function getLocationName(event) {
            event.preventDefault();
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(async function(position) {
                        const latitude = position.coords.latitude;
                        const longitude = position.coords.longitude;

                        // Call reverse geocoding
                        const locationName = await getLocationFromCoords(latitude, longitude);
                        document.getElementById("location").textContent = locationName;
                    },
                    function(error) {
                        alert("Error getting location: " + error.message);
                    });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }

        async function getLocationFromCoords(lat, lon) {
            return 'Under Construction';
            // Example using OpenCage Geocoder (requires API key)
            const apiKey = 'YOUR_API_KEY';
            const response = await fetch(`https://api.opencagedata.com/geocode/v1/json?q=${lat}+${lon}&key=${apiKey}`);
            const data = await response.json();

            if (data.results.length > 0) {
                return data.results[0].formatted; // e.g., "New York, United States"
            } else {
                return "Location not found";
            }
        }
    </script>

@endsection
