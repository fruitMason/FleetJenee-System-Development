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
                        <h3 class="page-title">Auto Part Usage Authorization</h3>
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


            <div class="row">
                <div class="col-md-12">
                    <section class="panel panel-default">
                        <div class="card mg-b-20" id="card_content">
                            <div class="card-body">
                                <div class="row mb-4">
                                    
                                    <div class="col-md-6">
                                        <div class="title">Auto Part:</div>
                                        <div class="text">{{ $auto_part_request->auto_part->name ?? 'N/A' }}
                                            ({{ $auto_part_request->auto_part->balance ?? 0 }})
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div>Requested By:</div>
                                        <div>{{ $auto_part_request->user->full_name() ?? 'N/A' }} </div>
                                    </div>
                                </div>

                                <div class="row mb-4">

                                    <div class="col-md-6">
                                        <div>Requested Qnt:</div>
                                        <div>{{ $auto_part_request->qnt_requested }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div>Approved Qnt:</div>
                                        {{ $auto_part_request->qnt_approved }}
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div>Request Date:</div>
                                        <div>
                                            {{ $auto_part_request->created_at->format('D, d F Y H:i A') }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div>End Date:</div>
                                        @if ($auto_part_request->status == 'pending')
                                            <div class='text-warning'>
                                            @else
                                                <div class='text-success'>
                                        @endif
                                        {{ ucwords($auto_part_request->status) }}
                                    </div>
                                </div>




                            </div>



                            {{-- finance detials form --}}
                            @if ($auto_part_request->status == 'pending')
                                <hr>
                                <form method="post" action="{{ route('inventory.usage.auth') }}"
                                    onsubmit="return SubmitDelete(this,'Validate Auto Part');" class="confirmationForm">
                                    <input type="hidden" name="id" value="{{ $auto_part_request->id }}">

                                    @csrf

                                    <div class="row mb-4">

                                        <div class="col-md-4">
                                            <div class="form-group form-focus select-focus">
                                                <div class="col-md-12">Auth Status<span class="text-danger">*</span>
                                                    <select class="select floating" name="status" required>
                                                        <option value="">-- Select Status --</option>
                                                        @foreach ($status as $yn)
                                                            @if (old('status', $auto_part_request->status) == $yn->value)
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

                                        <div class="col-md-4">
                                            Approved Qnt:<span class="text-danger">*</span>
                                            <input type="number" class="form-control" name="qnt_approved"
                                                value="{{ old('qnt_approved', $auto_part_request->qnt_requested) }}"
                                                step="1">
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group form-focus select-focus">
                                                <div class="col-md-12">Price Tag<span class="text-danger">*</span>
                                                    <select class="select floating" name="price_tag" required>
                                                        <option value="">-- Select Tag --</option>
                                                        @foreach ($price_tag as $yn)
                                                            @if (old('price_tag', $auto_part_request->price_tag) == $yn->cost)
                                                                <option value="{{ $yn->cost }}" selected>
                                                                    {{ number_format($yn->cost, 2) }} -
                                                                    {{ $yn->created_at->format('d/m/y') }}
                                                                </option>
                                                            @else
                                                                <option value="{{ $yn->cost }}">
                                                                    {{ number_format($yn->cost, 2) }} -
                                                                    {{ $yn->created_at->format('d/m/Y') }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>

                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                    <div class="row">

                                        <div class="col-md-8">Authority Note<span class="text-danger">*</span>
                                            <textarea class="form-control" name="fin_comment" id="fin_comment" rows="3" required>{{ old('fin_comment', $auto_part_request->fin_comment) }}</textarea>
                                        </div>




                                        <div class="col-md-12 " style="margin-top: 10px;">

                                            <button class="btn btn-primary submit-btn" type="submit">Submit</button>
                                        </div>
                                    </div>



                                </form>
                            @endif
                            {{-- end of finance form --}}





                        </div>
                </div>
                </section>
            </div>


        </div>
    </div>
    </div>
@endsection
