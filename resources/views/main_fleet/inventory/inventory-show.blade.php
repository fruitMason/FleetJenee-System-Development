@extends('layouts.master')

@section('page_title', 'Purchase Order Details')

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
                        <h3 class="page-title">Auto Part Statement</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"> <a href="{{ route('inventory.index') }}">Purchase Orders</a> </li>
                            <li class="breadcrumb-item active">Details</li>
                        </ul>
                    </div>
                    <div class="col-auto float-end ms-auto">
                        <a href="{{ route('inventory.index') }}" class="btn add-btn"><i class="fa fa-arrow-left"></i> Back
                            to List</a>
                    </div>
                </div>
            </div>



            <div class="row">
                <div class="col-md-12">
                    <section class="panel panel-default">
                        <div class="card mg-b-20" id="card_content">
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="title">Auto Part:</div>
                                        <div class="text"> {{ $auto->name }} </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div>Desc:</div>
                                        <div> {{ $auto->description }} </div>
                                    </div>
                                </div>

                                <div class="row mb-4">

                                    <div class="col-md-6">
                                        <div>Average Cost</div>
                                        <div> {{ number_format($auto->unit_cost, 2) }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div>Available Qnt(Psc):</div>

                                        <div class="fw-bold"> {{ $auto->balance }} </div>


                                    </div>
                                </div>

                            </div>
                        </div>

                    </section>
                </div>

            </div>




            {{-- table card --}}
            @if ($auto->autoPartStatement->isNotEmpty())
                <div class="row">
                    <div class="col-md-12">
                        <section class="panel panel-default">
                            <div class="card mg-b-20" id="card_content">

                                <div class="card-body">
                                    <div class="card-title">
                                        <h4>Auto Part Movement History</h4>
                                    </div>
                                    {{--  --}}
                                    {{-- {{$auto->autoPartStatement}} --}}
                                    <div class="table-responsive">
                                        <table class="table  table-hover table-sm">
                                            <thead class="bg-dark text-white">
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Date</th>
                                                    <th scope="col">Trans Type</th>
                                                    <th scope="col">Narration</th>
                                                    <th scope="col">StockIn</th>
                                                    <th scope="col">StockOut</th>
                                                    <th scope="col">Trans By</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($auto->autoPartStatement as $note)
                                                    <tr>
                                                        <th scope="row"> {{ $loop->iteration }}</th>
                                                        <td> {{ $note->created_at->format('D, d F Y') }} </td>
                                                        <td> {{ $note->trans_type }} </td>
                                                        <td> {{ $note->narration }} </td>
                                                        <td>{{ $note->stock_in }} </td>
                                                        <td>{{ $note->stock_out }} </td>
                                                         <td>{{ $note->user->full_name() }} </td>

                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </section>
                    </div>
                </div>
            @else
                <p>No statement !</p>
            @endif




        </div>
    </div>
    </div>
@endsection
