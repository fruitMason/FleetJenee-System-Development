@extends('layouts.master')

@section('page_title', 'View Maintenance Work Order')

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
                        <h3 class="page-title">Work Order Authorization</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"> <a href="{{ route('accounts.orders') }}">Work Orders</a> </li>
                            <li class="breadcrumb-item active">Authorization</li>
                        </ul>
                    </div>
                    <div class="col-auto float-end ms-auto">
                        <a href="{{ route('accounts.orders') }}" class="btn add-btn"><i class="fa fa-arrow-left"></i> Back
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
                                        <div class="text">{{ $maintenance->car->model ?? 'N/A' }}
                                            ({{ $maintenance->car->car_number ?? 'N/A' }}) </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div>Mechanic:</div>
                                        <div>{{ $maintenance->mechanic->full_name() ?? 'N/A' }}</div>
                                    </div>
                                </div>

                                <div class="row mb-4">

                                    <div class="col-md-6">
                                        <div>Maintenance Type:</div>
                                        <div>{{ $maintenance->type ?? 'N/A' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div>Mechanic Status:</div>
                                        @if ($maintenance->status == 'completed')
                                            <div class="text-success">{{ ucwords($maintenance->status) ?? 'N/A' }}</div>
                                        @else
                                            <div>{{ ucwords($maintenance->status) ?? 'N/A' }}</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div>Start Date:</div>
                                        <div>
                                            {{ $maintenance->start_date ? $maintenance->start_date->format('D, d F Y H:i A') : 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div>End Date:</div>
                                        <div>
                                            {{ $maintenance->end_date ? $maintenance->end_date->format('D, d F Y H:i A') : 'N/A' }}
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
                                            <div class='text-warning'>
                                            @else
                                                <div class='text-success'>
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
                                    <div>{{ $maintenance->fin_user ?? 'N/A' }}</div>
                                </div>
                            </div>


                            {{-- finance detials form --}}
                            @if ($notes->count() <= 0 || $maintenance->fin_status == 'pending')
                                <hr>
                                <form method="post" action="{{ route('accounts.orders.details.update') }}"
                                    onsubmit="return SubmitDelete(this,'Authorize Work Order');" class="confirmationForm"
                                    enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="{{ $maintenance->id }}">

                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-8">Authority Note<span class="text-danger">*</span>
                                                        <textarea class="form-control" name="fin_comment" id="fin_comment" rows="7" required>{{ old('fin_comment', $maintenance->fin_comment) }}</textarea>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group form-focus select-focus">
                                                            <div class="col-md-8">Finance Auth Status<span
                                                                    class="text-danger">*</span>
                                                                <select class="select floating" name="fin_status" required>
                                                                    <option value="">-- Select Fin Status --</option>
                                                                    @foreach ($fin_status as $yn)
                                                                        @if (old('fin_status', $maintenance->fin_status) == $yn->value)
                                                                            <option value="{{ $yn->value }}" selected>
                                                                                {{ $yn->text }}
                                                                            </option>
                                                                        @else
                                                                            <option value="{{ $yn->value }}">
                                                                                {{ $yn->text }}
                                                                            </option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>

                                                            </div>

                                                        </div>
                                                    </div>


                                                    <div class="col-md-12 " style="margin-top: 10px;">

                                                        <button class="btn btn-primary submit-btn"
                                                            type="submit">Submit</button>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                </form>
                            @endif
                            {{-- end of finance form --}}





                        </div>
                </div>
                </section>
            </div>

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
{{-- 
@section('js')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>
@endsection --}}
