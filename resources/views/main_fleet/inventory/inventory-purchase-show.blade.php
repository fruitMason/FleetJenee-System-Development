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
                        <h3 class="page-title">Auto Part Purchase Order Detail</h3>
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
                                        <div class="title">Car:</div>
                                        <div class="text"> $maintenance->car->model ?? 'N/A' }}
                                           </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div>Mechanic:</div>
                                        <div> $maintenance->mechanic->full_name() ?? 'N/A' }}</div>
                                    </div>
                                </div>

                                <div class="row mb-4">

                                    <div class="col-md-6">
                                        <div>Maintenance Type:</div>
                                        <div> $maintenance->type ?? 'N/A' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div>Mechanic Status:</div>
                                        @if ($maintenance->status == 'completed')
                                            <div class="fw-bold text-success"> ucwords($maintenance->status) ?? 'N/A' }}
                                            </div>
                                        @else
                                            <div class="fw-bold"> ucwords($maintenance->status) ?? 'N/A' }}</div>
                                        @endif

                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div>Start Date:</div>
                                        <div>
                                             $maintenance->start_date ? $maintenance->start_date->format('D, d F Y H:i A') : 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div>End Date:</div>
                                        <div>
                                     
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <div>Comments:</div>
                                        <div>{{ $maintenance->comment ?? 'N/A' }}</div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div>Finance Review Status:</div>
                                        @if ($maintenance->fin_status == 'pending')
                                            <div class='text-warning fw-bold'>
                                            @elseif ($maintenance->fin_status == 'declined')
                                                <div class='text-danger fw-bold'>
                                                @else
                                                    <div class='text-success fw-bold'>
                                        @endif

                                        {{ ucwords($maintenance->fin_status) }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div>Date Authorized:</div>
                                    <div>
                                        {{ $maintenance->fin_date ? $maintenance->fin_date->format('D, d F Y') : 'N/A' }}
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div>Finance Comments:</div>
                                    <div> {{ $maintenance->fin_comment }} </div>
                                </div>

                                <div class="col-md-6">
                                    <div>Finance Officer:</div>
                                    <div>{{ $finUser ?? 'N/A' }}</div>
                                </div>
                            </div>



                        </div>

                    </section>
                </div>
                @if (Auth::user()->id == $maintenance->user_id &&
                        $maintenance->status == 'pending' &&
                        $maintenance->fin_status == 'pending')
                    <form class="mb-3" action="{{ route('fleet.vehicle.maintenance.delete', $maintenance->id) }}" method="POST"
                        onsubmit="return SubmitDelete(this,'Delete Pending Work Order Request');">
                        @method('delete')
                        @csrf
                        <button class="btn btn-danger" type="submit">Delete Pending Request</button>
                    </form>
                @endif

                @if (Auth::user()->id == $maintenance->user_id &&
                        $maintenance->status == 'pending' &&
                        $maintenance->fin_status == 'approved')
                    <form class="mb-3" action="{{ route('fleet.vehicle.maintenance.complete', $maintenance->id) }}" method="POST"
                        onsubmit="return SubmitDelete(this,'Completed Pending Work Order Request');">
                        @method('patch')
                        @csrf
                        <button class="btn btn-success" type="submit">Update Mechanic Status [Completed]</button>
                    </form>
                @endif
            </div>


            {{-- table card --}}
            @if ($notes->count() > 0)
                <div class="row">
                    <div class="col-md-12">
                        <section class="panel panel-default">
                            <div class="card mg-b-20" id="card_content">

                                <div class="card-body">
                                    <div class="card-title">
                                        <h4>Mechanic History</h4>
                                    </div>
                                    {{--  --}}
                                    <div class="table-responsive">
                                        <table class="table  table-hover table-sm">
                                            <thead class="bg-dark text-white">
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Date</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Comment</th>
                                                    <th scope="col">User</th>
                                                    <th scope="col">Media</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($notes as $note)
                                                    <tr>
                                                        <th scope="row"> {{ $loop->iteration }}</th>
                                                        <td> {{ $note->receipt_date->format('D, d F Y') }} </td>
                                                        <td> {{ $note->status }} </td>
                                                        <td>{{ $note->receipt_comment }} </td>
                                                        <td>{{ $note->first_name }} {{ $note->middle_name }}
                                                            {{ $note->last_name }} </td>
                                                        <td>

                                                            @if ($note->media)
                                                                <a class="btn btn-xs btn-success"
                                                                    href="{{ route('downloader', ['path' => $note->media->path]) }}"
                                                                    target="_blank"><i class="fa fa-eye text-white"
                                                                        aria-hidden="true"></i></a>
                                                            @endif


                                                        </td>
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

            @endif





        </div>
    </div>
    </div>
@endsection
