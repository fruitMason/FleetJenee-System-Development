<div id="edit_modal" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New User</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="edit_modal_content">
                <form method="post" id="form_edit" onsubmit="return false;">
                    <input type="hidden" id="edit_id">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="col-form-label">First Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="edit_first_name" value=""
                                            placeholder="Enter First Name">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="col-form-label">Middle Name</label>
                                        <input type="text" class="form-control" id="edit_middle_name" value=""
                                            placeholder="Enter Middle Name">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="col-form-label">Last Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="edit_last_name" value=""
                                            placeholder="Enter Last Name">
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="col-form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="edit_email" value=""
                                            placeholder="Enter Email">
                                    </div>

                                    {{--                                    <div class="col-md-4"> --}}
                                    {{--                                        <label class="col-form-label">Password <span class="text-danger">*</span></label> --}}
                                    {{--                                        <input type="password" class="form-control" id="edit_password" value="" placeholder="Enter Password"> --}}
                                    {{--                                    </div> --}}

                                    <div class="col-md-6">
                                        <label class="col-form-label">Phone Number</label>
                                        <input type="text" class="form-control" id="edit_mobile" value=""
                                            placeholder="Enter Phone Number">
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="row">

                                     
                                  

                                    <div class="col-md-4">
                                        <label class="col-form-label">User Role Permissions <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control select" id="edit_role" required>
                                            <option>-- select role --</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="col-form-label">Department <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control edit_select_search" id="edit_department_id"
                                            required>
                                            <option>-- select department --</option>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                   
                                    <div class="col-md-4">
                                        <label class="col-form-label">User Login Type <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control select" name="type" id="user_type"
                                            onchange="toggleDriverType()" required>
                                            <option value="">-- select Type --</option>
                                            <option value="DRIVER">Driver Login</option>
                                            <option value="ADMINISTRATOR">Fleet Manager Login</option>
                                            <option value="MECHANIC">Mechanic Login</option>
                                            <option value="ACCOUNT">Finance Manager Login</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Driver Type Container -->
                            <div class="form-group" id="driver_type_container">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="col-form-label">Driver Type</label>
                                        <select class="form-control select" id="edit_driver_type">
                                            <option value="">-- select driver type --</option>
                                            <option value="DEPARTMENT_HEAD">Department Head</option>
                                            <option value="EMPLOYED_DRIVER">Employed Driver</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="col-form-label">License Class</label>
                                        <input type="text" class="form-control" id="edit_license_class"
                                            value="" placeholder="Enter License Class">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="col-form-label">License Number</label>
                                        <input type="text" class="form-control" id="edit_license_number"
                                            value="" placeholder="Enter License Number">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="col-form-label">License Expiry</label>
                                        <input type="date" id="edit_license_expiry" class="form-control"
                                            value="">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="col-form-label">Vendor</label>
                                        <select class="form-control edit_select_search" id="edit_vendor_id">
                                            <option>-- select vendor --</option>
                                            @foreach ($vendors as $vendor)
                                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn" id="btnEdit">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
