<div id="request_car_modal" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Request New Car</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form_create" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-form-label">Request For <span class="text-danger">*</span></label>
                                        <select class="form-control create_select_search" name="user_id" required>
                                            <option>-- Select User --</option>
                                            @foreach($users as $user) 
                                                <option value="{{$user->id}}">{{$user->full_name()}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="col-form-label">Request Date <span class="text-danger">*</span></label>
                                        <input type="date" name="date_needed" class="form-control" value="{{today()->toDateString()}}">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="col-form-label">Return Date <span class="text-danger">*</span></label>
                                        <input type="date" name="return_date" class="form-control" value="">
                                    </div>
                                </div>

                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">Request Purpose <span class="text-danger">*</span>
                                        <textarea class="form-control" name="request_reason" rows="7"></textarea>
                                    </div>
                                </div>

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
