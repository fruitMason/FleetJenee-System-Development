<div id="add_vendor_modal" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Service Provider</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form  method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-form-label">Service Provider Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" value="" placeholder="Enter Service Provider Name">
                                    </div>

{{--                                    <div class="col-md-4">--}}
{{--                                        <label class="col-form-label">Middle Name</label>--}}
{{--                                        <input type="text" class="form-control" name="middle_name" value="" placeholder="Enter Middle Name">--}}
{{--                                    </div>--}}

{{--                                    <div class="col-md-4">--}}
{{--                                        <label class="col-form-label">Last Name <span class="text-danger">*</span></label>--}}
{{--                                        <input type="text" class="form-control" name="last_name" value="" placeholder="Enter Last Name">--}}
{{--                                    </div>--}}
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="col-form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="email" value="" placeholder="Enter Email">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="col-form-label">Phone Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="phone_number" value="" placeholder="Enter Phone Number">
                                    </div>

                                    <div class="col-md-4">Location
                                        <textarea class="form-control" name="address" rows="7"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="col-form-label">Service Type <span class="text-danger">*</span></label>
                                        <select class="form-control select" name="service_type" required>
                                            <option>-- select service type --</option>
                                            <option>Mechanics</option>
                                            <option>Spare Part Dealer</option>
                                            <option>Towing Service</option>
                                            <option>Insurance Company</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="col-form-label">Region <span class="text-danger">*</span></label>
                                        <select class="form-control select" name="region_id" required>
                                            <option>-- select region --</option>
                                            @foreach($regions as $region)
                                                <option value="{{$region->id}}">{{$region->name}}</option>
                                            @endforeach
                                        </select>
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
