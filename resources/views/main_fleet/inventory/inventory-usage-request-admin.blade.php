@extends('layouts.master')

@section('page_title', 'Auto Part Usage Authorization')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}">
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="content container-fluid">
            <div class="col-md-12">
                @include('includes.error')
            </div>

            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Auto Part Usage Request</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"> <a href="{{ route('inventory.usage.index') }}">Usage Requests</a>
                            </li>
                            <li class="breadcrumb-item active">Usage Authorization</li>
                        </ul>
                    </div>
                    <div class="col-auto float-end ms-auto">
                        <a href="{{ route('inventory.usage.index') }}" class="btn add-btn"><i class="fa fa-arrow-left"></i>
                            Back
                            to List</a>
                    </div>
                </div>
            </div>


            <form action="{{ route('inventory.usage.store') }}"
                onsubmit="return SubmitDelete(this,'Save Auto Part Usage Request');" method="POST">
                @csrf
                <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="col-form-label">Requester <span class="text-danger">*</span></label>
                                    <input type="text" readonly class="form-control" name="requester"
                                        value="{{ auth()->user()->full_name() }}">
                                </div>


                                <div class="col-md-6">
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

                            <div class="row">
                                <div class="col-md-3">
                                    <label class="col-form-label">Request Date <span class="text-danger">*</span></label>
                                    <input type="date" name="date_needed" class="form-control"
                                        value="{{ today()->toDateString() }}" disabled>
                                </div>

                                <div class="col-md-3">
                                    <label class="col-form-label">Request Quantity <span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="qnt_requested" class="form-control"
                                        value="{{ old('qnt_requested', 1) }}" step="1" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="col-form-label" for="auto_part_id">
                                        Auto Part <span class="text-danger">*</span>
                                    </label>
                                    <div class="form-group">
                                        <select id="auto_part_id" name="auto_part_id" class="select user-search2"
                                            style="width: 100%;" required>
                                            <option value="">Select an auto part</option>
                                            @foreach ($autoParts as $part)
                                                <option value="{{ $part->id }}" data-price="{{ $part->unit_cost }}">
                                                    {{ $part->name }}</option>
                                            @endforeach
                                        </select>


                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">Request Purpose <span class="text-danger" required>*</span>
                                    <textarea class="form-control" name="reason_for_request" rows="7"></textarea>
                                </div>
                            </div>

                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
