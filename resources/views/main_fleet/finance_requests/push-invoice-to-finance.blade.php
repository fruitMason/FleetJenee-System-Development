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
                        <h3 class="page-title">Notify Account To Pay</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"> <a href="{{ route('finance.invoice.index') }}">Requests</a> </li>
                            <li class="breadcrumb-item active">Push maintenanc invoice</li>
                        </ul>
                    </div>
                    <div class="col-auto float-end ms-auto">
                        <a href="{{ route('finance.invoice.index') }}" class="btn add-btn"><i class="fa fa-arrow-left"></i>
                            Back
                            to List</a>
                    </div>
                </div>
            </div>



            <section class="panel panel-default">
                <div class="card mg-b-20" id="card_content">
                    <div class="card-body">
                        <div class="card-title">Request Detail </div>


                        <form method="post" action="{{ route('finance.invoice.submittofinance') }}"
                            onsubmit="return SubmitDelete(this,'Notify Finance To Pay');" class="confirmationForm"
                            enctype="multipart/form-data">

                            @csrf
                            <input type="hidden" name="tidnew" value="{{ $invoice->id }}">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="col-form-label">Invoice #
                                        </label>
                                        <input type="text" class="form-control" id="invoice"
                                            value="{{ $invoice->invoice_number }}" disabled>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="col-form-label">Amount </label>
                                        <input type="text" class="form-control" id="total"
                                            value="{{ $invoice->net_total }}" disabled>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="col-form-label">Invoice Date | Reference </label>
                                        <input type="text" class="form-control" id="total"
                                            value="{{ $invoice->due_date->format('d-m-Y') }} | {{ $invoice->reference }}"
                                            disabled>
                                    </div>


                                </div>
                            </div>



                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label class="col-form-label">Work Order Details </label>
                                    <input type="text" class="form-control" id="total"
                                        value="{{ $invoice->car_maintenance->car->model }} ({{ $invoice->car_maintenance->car->car_number }}) - {{ $invoice->car_maintenance->start_date->format('d-m-Y') }} | {{ $invoice->car_maintenance->comment }}  "
                                        disabled>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="notify_user" class="d-block mb-2"> Notify User(Finance Officer) via SMS?
                                        </label>

                                        <select class="select floating user-search2" style="width: 100%;"
                                            name="notify_user">
                                            <option value="">Select User</option>
                                            @foreach ($users as $user)
                                                @if (old('notify_user') == $user->id)
                                                    <option value="{{ $user->id }}" selected>
                                                        {{ str_replace('  ', ' ', ucwords($user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name . ' - ' . $user->type)) }}
                                                    </option>
                                                @else
                                                    <option value="{{ $user->id }}">
                                                        {{ str_replace('  ', ' ', ucwords($user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name . ' - ' . $user->type)) }}
                                                    </option>
                                                @endif
                                            @endforeach


                                        </select>
                                    </div>
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

            $('.user-search2').select2();

        });
    </script>


@endsection
