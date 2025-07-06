<div id="add_car_modal" class="modal custom-modal fade create_modal" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Car</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form  method="post" id="form_create">
                    @csrf
                    <input type="hidden" class="form-control" name="carID" value="">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-8">Car Make and Model <span class="text-danger">*</span>
                                        <input type="text" class="form-control" name="model" value="" placeholder="Enter make and model">
                                    </div>
                                    <div class="col-md-4">
                                        Make Year <span class="text-danger">*</span>
                                        <input type="month" class="form-control" name="year" value="" placeholder="Enter year" required>
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">Car Body Style
                                        <input type="text" class="form-control" name="body_style" value="" placeholder="Enter body style">
                                    </div>

                                    <div class="col-md-4">Trim Level
                                        <input type="text" class="form-control" name="trim_level" value="" placeholder="Enter Trim Level">
                                    </div>

                                    <div class="col-md-4">Color
                                        <input type="text" class="form-control" name="color" placeholder="Enter car color" value="" required>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">Car Number <span class="text-danger">*</span>
                                        <input type="text" class="form-control" placeholder="Enter car number" value="" name="car_number" required>
                                    </div>
                                    <div class="col-md-4">Chassis Number
                                        <input type="text" class="form-control" placeholder="Enter chasis number" value="" name="chassis" required>
                                    </div>

                                    <div class="col-md-4">Start Odometer <span class="text-danger">*</span>
                                        <input type="number" class="form-control" placeholder="Enter odometer reading" value="" name="odometer" required>
                                    </div>
                                </div>
                            </div>
                            

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">Engine Capacity
                                        <input type="text" class="form-control" placeholder="Enter engine capacity" value="" name="engine_capacity" required>
                                    </div>

                                    <div class="col-md-4">Fuel Type
                                        <select class="form-control select" name="fuel_type" required>
                                            <option value="petrol">Petrol</option>
                                            <option value="diesel">Diesel</option>
                                        </select>
                                    </div>
{{--                                    <div class="col-md-4">Tank size--}}
{{--                                        <input type="text" class="form-control" placeholder="Enter tank size" value="" name="tank_size" required>--}}
{{--                                    </div>--}}

                                <div class="col-md-4">
                                    <div class="form-group">User Assigned
                                        <select class="form-control create_select_search" name="user_id" required>
                                            <option value="0">-- select user/driver --</option>
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}">{{$user->full_name()}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

{{--                            <div class="form-group">--}}
{{--                                <div class="row">--}}
{{--                                    <div class="col-md-4">Cost of Car--}}
{{--                                        <input type="number" class="form-control" placeholder="Enter car cost" name="car_cost" value="" required>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-md-4">Purchase Date--}}
{{--                                        <input type="date" class="form-control" placeholder="Enter purchase date"  name="purchase_date" value="" required>--}}
{{--                                    </div>--}}

{{--                                    <div class="col-md-4">State of Car--}}
{{--                                        <input type="text" class="form-control" placeholder="Enter state of car"  name="condition" value="" required>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="form-group">--}}
{{--                                <div class="row">--}}
{{--                                    <div class="col-md-4">Sticker Code--}}
{{--                                        <input type="text" name="dvla_code" placeholder="Enter DVLA code"  class="form-control" value=""  >--}}
{{--                                    </div>--}}

{{--                                    <div class="col-md-4">Expiry Date--}}
{{--                                        <input type="date" name="dvla_expiry" class="form-control" value=""  >--}}
{{--                                    </div>--}}

{{--                                   --}}
{{--                                </div>--}}
{{--                            </div>--}}

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">Road Worthy Beginning Date <span class="text-danger">*</span>
                                        <input type="date" name="road_worthy_start_date" class="form-control" value="" required>
                                    </div>

                                    <div class="col-md-4">Road Worthy Expiry Date <span class="text-danger">*</span>
                                        <input type="date" name="road_worthy_expiry_date" class="form-control" value=""  required>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">Status of Car
                                            <select class="form-control select" name="status" required>
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
                                        <textarea class="form-control" name="comment" rows="7"></textarea>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12">Insurance Start Date
                                                    <input type="date" name="insurance_start_date" class="form-control" value="" >
                                                </div>

                                                <div class="col-md-12 mt-3">Insurance Expiry Date
                                                    <input type="date" name="insurance_expiry" class="form-control" value=""  >
                                                </div>
                                                <div class="col-md-12 mt-3">Car Group <span class="text-danger">*</span>
                                                    <select class="form-control" name="car_group" required>
                                                        <option value="pool" {{ old('car_group', $car->car_group ?? '') == 'pool' ? 'selected' : '' }}>Pool</option>
                                                        <option value="assigned" {{ old('car_group', $car->car_group ?? '') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="submit-section">
                                    <button class="btn btn-primary submit-btn">Submit</button>
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
