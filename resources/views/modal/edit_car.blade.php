<div id="edit_modal" class="modal custom-modal fade create_modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Car</h5>
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
                                    <div class="col-md-8">Car Make and Model <span class="text-danger">*</span>
                                        <input type="text" class="form-control" id="edit_model" value="" placeholder="Enter make and model">
                                    </div>
                                    <div class="col-md-4">Make Year <span class="text-danger">*</span>
                                        <input type="month" class="form-control" id="edit_year" value="" placeholder="Enter year">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">Car Body Style
                                        <input type="text" class="form-control" id="edit_body_style" value="" placeholder="Enter body size">
                                    </div>

                                    <div class="col-md-4">Trim Level
                                        <input type="text" class="form-control" id="edit_trim_level" value="" placeholder="Enter Trim Level">
                                    </div>

                                    <div class="col-md-4">Color
                                        <input type="text" class="form-control" id="edit_color" placeholder="Enter car color" value="" required>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">Car Number <span class="text-danger">*</span>
                                        <input type="text" class="form-control" placeholder="Enter car number" value="" id="edit_car_number" required>
                                    </div>
                                    <div class="col-md-4">Chassis Number
                                        <input type="text" class="form-control" placeholder="Enter chasis number" value="" id="edit_chassis" required>
                                    </div>

                                    <div class="col-md-4">Start Odometer <span class="text-danger">*</span>
                                        <input type="number" class="form-control" placeholder="Enter odometer reading" value="" id="edit_odometer" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">Engine Capacity
                                        <input type="text" class="form-control" placeholder="Enter engine capacity" value="" id="edit_engine_capacity" required>
                                    </div>

                                    <div class="col-md-4">Fuel Type
                                        <select class="form-control select" id="edit_fuel_type" required>
                                            <option value="petrol">Petrol</option>
                                            <option value="diesel">Diesel</option>
                                        </select>
                                    </div>

                                <div class="col-md-4">
                                    <div class="form-group">User Assigned
                                        <select class="form-control edit_select_search" id="edit_user_id" required>
                                            <option value="0">-- select user/driver --</option>
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}">{{$user->full_name()}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">Road Worthy Beginning Date <span class="text-danger">*</span>
                                        <input type="date" id="edit_road_worthy_start_date" class="form-control" value="" >
                                    </div>

                                    <div class="col-md-4">Road Worthy Expiry Date <span class="text-danger">*</span>
                                        <input type="date" id="edit_road_worthy_expiry_date" class="form-control" value=""  >
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">Status of Car
                                            <select class="form-control select" id="edit_status" required>
                                                <option value="active" selected>active</option>
                                                <option value="inactive">inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-8">Comment About Car
                                        <textarea class="form-control" id="edit_comment" rows="7"></textarea>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12">Insurance Start Date
                                                    <input type="date" id="edit_insurance_start_date" class="form-control" value="" >
                                                </div>

                                                <div class="col-md-12 mt-3">Insurance Expiry Date
                                                    <input type="date" id="edit_insurance_expiry" class="form-control" value=""  >
                                                </div>

                                                <div class="col-md-12 mt-3">Car Group <span class="text-danger">*</span>
                                                    <select class="form-control" id="edit_car_group" name="car_group" required>
                                                        <option value="">-- Select Car Group --</option>
                                                        <option value="pool">Pool</option>
                                                        <option value="assigned">Assigned</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="submit-section">
                                    <button class="btn btn-primary submit-btn" id="btnEdit">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
