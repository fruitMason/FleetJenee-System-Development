@extends('layouts.master')
@section('page_title', 'Role Permissions')
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
                        <h3 class="page-title">Role Permissions</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Settings</a></li>
                            <li class="breadcrumb-item active">Role Permissions</li>
                        </ul>
                    </div>
                    <div class="col-auto float-end ms-auto">
                        <a href="#" class="btn add-btn" data-bs-toggle="modal"
                            data-bs-target="#add_permission_modal"><i class="fa fa-plus"></i> Add New Permission</a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <section class="panel panel-default">

                        <div class="card mg-b-20" id="card_content">

                            <div class="card-body">

                                <div class="row" style="padding-top: 10px;">
                                    <div class="col-md-6" style="padding-bottom: 10px;">
                                        <div class="form-group">
                                            <label for="name" class="col-md-4 control-label">Name:</label>
                                            <div class="col-md-8">
                                                <input name="name" value="{{ $role->name }}" type="text"
                                                    class="form-control" placeholder="" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="guard_name" class="col-md-4 control-label">Guard:</label>
                                            <div class="col-md-8">
                                                <input name="guard_name" value="{{ $role->guard_name }}" type="text"
                                                    class="form-control" placeholder="" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="mt0 mb10" />

                                <div class="container">

                                    <h3> <em> <input type="checkbox" multiple value="" id="selectAll"> Select All
                                            Permissions</em></h3>

                                    <br>
                                    <br>
                                    @php
                                        // Group the items by module
                                        $groupedItems = collect($permissions)
                                            ->groupBy('module')
                                            ->map(function ($moduleItems) {
                                                return $moduleItems->sortBy('id');
                                            });
                                    @endphp

                                    @foreach ($groupedItems as $module => $moduleItems)
                                        <div class="menu-group">
                                            <h3 class="module-title">{{ ucfirst($module) }}</h3>
                                            {{-- <ul class="menu-items">                                                 --}}
                                            @foreach ($moduleItems as $item)
                                                <div class="col-md-4" style="padding-bottom: 1em">
                                                    <input class="permissions" type="checkbox" name="permissions[]"
                                                        id="permission_{{ $item['id'] }}" value="{{ $item['id'] }}"
                                                        @if (
                                                            \Spatie\Permission\Models\Role::findById(request()->segment(count(request()->segments())))->hasPermissionTo(
                                                                $item['id'])) checked @endif>
                                                    <label class="form-check-label"
                                                        for="permission_{{ $item['id'] }}">&nbsp;{{ $item['name'] }}&nbsp;&nbsp;</label>
                                                </div>
                                            @endforeach
                                            {{-- </ul> --}}
                                        </div>
                                    @endforeach
                                    {{-- {{ $permissions }} --}}

                                    {{-- <div class="row">
                                        @foreach ($permissions as $permission)
                                            <div class="col-md-4" style="padding-bottom: 1em">
                                                <input class="permissions" type="checkbox" name="permissions[]"
                                                    id="permission_{{ $permission->id }}" value="{{ $permission->id }}"
                                                    @if (\Spatie\Permission\Models\Role::findById(request()->segment(count(request()->segments())))->hasPermissionTo($permission->id)) checked @endif>
                                                <label class="form-check-label"
                                                    for="permission_{{ $permission->id }}">&nbsp;{{ $permission->name }}&nbsp;&nbsp;</label>
                                            </div>
                                        @endforeach
                                    </div> --}}
                                </div>

                                <br>
                                <hr>

                                <div style="margin-bottom: 15px; padding-left: 10px;">
                                    <button id="submitData" class="btn btn-primary"><i
                                            class="fa fa-check-square-o"></i>SUBMIT DATA</button>
                                </div>

                            </div>
                        </div>

                    </section>
                </div>
            </div>
        </div>

        @include('modal.add_permission')
    </div>

@endsection

@section('js')

    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>


    <script>
        "use strict";

        $('#selectAll').on('change', function(v) {

            let selectAll = $(this);

            if (selectAll.hasClass('checkedAll')) {
                $('.permissions').prop('checked', false)
                selectAll.removeClass('checkedAll');
            } else {
                $('.permissions').prop('checked', true)
                selectAll.addClass('checkedAll');
            }
        });

        $('#submitData').on('click', function() {
            let permissions = [];

            $(".permissions:checked").map(function() {
                permissions.push($(this).val());
            }).get().join(',');


            if (permissions.length < 1) {
                show_toast('Caution!', 'Please Select One or More Permissions', 'warning');
                return;
            }


            ajax('{{ route('settings.role.permissions.sync') }}', {
                role_id: getLastPathVar(),
                permissions: permissions,
                _token: _token
            }, 'card_content', function(response) {
                if (response.status === 200) {
                    $('#create_modal').modal('hide');
                    resetForm('form_create');
                    show_toast('Success', response.message, 'success');
                    setTimeout(function() {
                        window.location = '{{ route('settings.roles') }}'
                    }, 200);
                }
            });
        })

        function getLastPathVar() {
            return '{{ request()->segment(count(request()->segments())) }}'
        }
    </script>
@endsection
