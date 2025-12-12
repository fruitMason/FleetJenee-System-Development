@extends('layouts.master')
@section('page_title', 'Garage')
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
                    <h3 class="page-title">Garage</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Fleet</a></li>
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

</div>

@endsection

@section('js')
    {!! $dataTable->scripts() !!}

    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>
@endsection
