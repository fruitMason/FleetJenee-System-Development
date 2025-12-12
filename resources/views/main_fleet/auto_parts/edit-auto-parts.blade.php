@extends('layouts.master')
@section('page_title', 'Accounts Invoice')
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
                        <h3 class="page-title">Edit {{ $autopart->name }}</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('auto.parts.index') }}">Auto Parts</a></li>
                            <li class="breadcrumb-item active">New Auto Part</li>
                        </ul>
                    </div>

                    <div class="col-auto float-end ms-auto ">
                        <a href="{{ route('auto.parts.index') }}" class="btn add-btn"><i class="fa fa-arrow-left"></i>
                            Back To List</a>
                    </div>
                </div>
            </div>






            <section class="panel panel-default">
                <div class="card mg-b-20" id="card_content">
                    <div class="card-body">
                        <div class="card-title">{{ $autopart->name }}</div>


                        <form method="post" action="{{ route('auto.parts.update', $autopart->id) }}"
                            onsubmit="return SubmitDelete(this,'Update Auto Part');">
                            @csrf
                            @method('PATCH')

                            <!-- First Row - 3 equal columns -->
                            <div class="row mb-3">
                                <!-- Input 1 -->
                                <div class="col-md-4">
                                    <label class="col-form-label">Auto Part Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name"
                                        value="{{ old('name', $autopart->name) }}" placeholder="Enter Auto Part">
                                </div>

                                <div class="col-md-4">
                                    <label class="col-form-label">Average Cost<span class="text-danger">*</span></label>
                                    <input type="number" name="unit_cost" id="unit_cost" step="0.1"
                                        class="form-control" required value="{{ old('unit_cost', $autopart->unit_cost) }}">
                                </div>
                            </div>


                            <div class="mb-3 row">
                                <div class="col-md-8">
                                    <label for="descriptiont" class="d-block mb-2">
                                        Description
                                    </label>
                                    <textarea class="form-control" name="description" id="description" rows="5">{{ old('description', $autopart->description) }}</textarea>
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
