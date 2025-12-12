@extends('layouts.master')

@section('page_title', 'Inventory')

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
                        <h3 class="page-title">Available Auto Parts</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"> <a href="{{ route('auto.parts.index') }}">Auto Parts</a> </li>
                            <li class="breadcrumb-item active">Auto Parts Available - Inventory</li>
                        </ul>
                    </div>
                    <div class="col-auto float-end ms-auto"> 
                        <a href="{{ route('finance.invoice.create') }}" class="btn add-btn"><i class="fas fa-tools"></i>
                            Purchase Request - Stock In
                        </a>
                        
                        
                    </div>
                </div>
            </div>


            <div class="row filter-row">
                <div class="col-md-3">
                    <div class="form-group form-focus select-focus">
                        <select class="select floating" id="paymentTypeGroupFilter">
                            <option value="">-- Part Status --</option>
                            <option value="available">Available</option>
                            <option value="out">Out Of Stock</option> 
                        </select>
                        <label class="focus-label">Status</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <button type="button" id="btnStatusFilter" class="btn btn-success w-100"> Filter </button>
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


    <script>
        $(document).ready(function() {

            $('#btnStatusFilter').on('click', function() {
                var filterValue = $('#paymentTypeGroupFilter').val();
                
                console.log(filterValue);

                $('#dataTableBuilder').DataTable().ajax.url(
                    '{{ route('inventory.index') }}?filter=' + filterValue).load();
            });
        });
    </script>

@endsection
