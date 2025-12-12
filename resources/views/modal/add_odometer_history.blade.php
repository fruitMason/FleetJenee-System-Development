<div id="add_odometer_history_modal" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Odometer</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal_body">
                <form method="post" action="{{route('driver.odometer')}}">
                    @csrf
                    <input type="hidden" name="user_id" value="{{$car->user->id}}"/>
                    <input type="hidden" name="car_id" value="{{$car->id}}"/>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="col-form-label">User <span class="text-danger">*</span></label>
                                        <input type="text" name="user" class="form-control" value="{{$car->user->full_name()}}" disabled>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="col-form-label">Car <span class="text-danger">*</span></label>
                                        <input type="text" name="user" class="form-control" value="{{$car->model}} ({{$car->car_number}})" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="col-form-label">Old Odometer Value </label>
                                        <input type="text" name="old_odometer" class="form-control" value="{{$car->user->car->odometer}}" disabled>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="col-form-label">New Odometer Value <span class="text-danger">*</span></label>
                                        <input type="text" name="new_value" class="form-control">
                                    </div>
                                </div>

                            </div>

                            <div class="form-group">
                                <div class="submit-section">
                                    <button class="btn btn-primary submit-btn">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
