<div id="approve_car_modal" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approving Car Request</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal_body">
                <form method="post" enctype="multipart/form-data" action="{{route('fleet.vehicle.request.approve')}}">
                    @csrf
                    <input type="hidden" id="approve_car_request_id" name="approve_car_request_id"/>
                    <input type="hidden" id="approve_car_user_id" name="approve_car_user_id"/>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-form-label">Request For : </label>
                                <input type="text" class="form-control" id="requester" disabled>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-form-label">Reason For Request </label>
                                <input type="text" class="form-control" id="reason_for_request" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                            <label class="col-form-label">Assign Car <span class="text-danger">*</span></label>
                                            <select class="form-control select" name="car_id" required>
                                                <option>-- select car --</option>
                                                @foreach($cars as $car)
                                                    <option value="{{$car->id}}">{{$car->model}} ({{$car->car_number}})</option>
                                                @endforeach
                                            </select>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-form-label">Comment</label>
                                        <textarea class="form-control" name="comment" rows="5"></textarea>
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
