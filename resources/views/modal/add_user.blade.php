    <div id="add_user_modal" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New User</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" id="form_create" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" class="form-control" name="carID" value="">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="col-form-label">First Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="first_name"
                                                placeholder="Enter First Name" required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="col-form-label">Middle Name</label>
                                            <input type="text" class="form-control" name="middle_name"
                                                placeholder="Enter Middle Name">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="col-form-label">Last Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="last_name"
                                                placeholder="Enter Last Name" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <label class="col-form-label">Email <span
                                                    class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email"
                                                placeholder="Enter Email" required>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="col-form-label">Phone Number</label>
                                            <input type="text" class="form-control" name="mobile"
                                                placeholder="233246000000" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <span class="text-xs" style="color: gray;">Please ensure to select the User Role
                                        Permision that relates to the User Login Type</span>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="col-form-label">User Login Type <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control create_select_search" name="type" id="user_type"
                                                onchange="toggleDriverType()" required>
                                                <option value="">-- select Type --</option>
                                                <option value="DRIVER">Driver Login</option>
                                                <option value="ADMINISTRATOR">Fleet Manager Login</option>
                                                <option value="MECHANIC">Mechanic Login</option>
                                                <option value="ACCOUNT">Finance Manager Login</option>
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="col-form-label">User Role Permissions <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control create_select_search" name="role" required>
                                                <option>-- select role --</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="col-form-label">Department <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control create_select_search" name="department_id"
                                                required>
                                                <option>-- select department --</option>
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->id }}">{{ $department->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>


                                    </div>
                                </div>

                                <!-- Driver Type Dropdown -->
                                <div class="form-group" id="driver_type_container" style="display: none;">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="col-form-label">Driver Type</label>
                                            <select class="form-control select" name="driver_type" id="driver_type">
                                                <option value="">-- select driver type --</option>
                                                <option value="DEPARTMENT_HEAD">Department Head</option>
                                                <option value="EMPLOYED_DRIVER">Employed Driver</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="col-form-label">License Class</label>
                                            <input type="text" class="form-control" name="license_class"
                                                placeholder="Enter License Class">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="col-form-label">License Number</label>
                                            <input type="text" class="form-control" name="license_number"
                                                placeholder="Enter License Number">
                                        </div>

                                        <div class="col-md-4">
                                            <label class="col-form-label">License Expiry</label>
                                            <input type="date" name="license_expiry" class="form-control">
                                        </div> 
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                         <div class="col-md-4">
                                            <label class="col-form-label">Vendor</label>
                                            <select class="form-control create_select_search" name="vendor_id"
                                                id="dependentSelect">
                                                <option>-- select vendor --</option>
                                                @foreach ($vendors as $vendor)
                                                    <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>


                                        <div class="col-md-8">
                                            <label class="col-form-label">User Image </label>
                                            <div class="form-control "> <!--image-upload-wrap-->
                                                <input class="form-control-file" id="file" name="file"
                                                    type='file'
                                                    accept="image/*" />
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="submit-section">
                                    <button class="btn btn-primary submit-btn">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
