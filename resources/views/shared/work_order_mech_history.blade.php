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
                        <h3 class="page-title">Work Order Details - Diagnosis</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"> <a href="{{ route($prevUrl) }}">{{ $crumbHeading }}</a> </li>
                            <li class="breadcrumb-item active">Details</li>
                        </ul>
                    </div>
                    <div class="col-auto float-end ms-auto">
                        <a href="{{ route($prevUrl) }}" class="btn add-btn"><i class="fa fa-arrow-left"></i> Back
                            to List</a>
                    </div>
                </div>
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
                                                                    href="{{ route('downloader', ['path' => $note->media]) }}"
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
