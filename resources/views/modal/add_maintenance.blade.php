<div id="add_maintenance_modal" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Create Work Order</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal_body">
                <form method="post" class="maintenanceForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-form-label">Car <span class="text-danger">*</span></label>
                                        <select class="form-control select" name="car_id" id="car_id" required disabled>
                                            <option>-- select car --</option>
                                            @foreach($cars as $car)
                                                <option value="{{$car->id}}">{{$car->model}} || {{$car->car_number}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="col-form-label">Type <span class="text-danger">*</span></label>
                                        <select class="form-control select" name="type" id="type" required>
                                            <option>-- select maintenance type --</option>
                                            <option value="breakdown">Break Down</option>
                                            <option value="normal">Normal Routine</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="col-form-label">Mechanic <span class="text-danger">*</span></label>
                                        <select class="form-control select" name="mechanic_id" id="mechanic_id" required>
                                            <option>-- select mechanic --</option>
                                            @foreach($mechanics as $mechanic)
                                                <option value="{{$mechanic->id}}">{{$mechanic->full_name()}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-8">Comment <span class="text-danger">*</span>
                                        <textarea class="form-control" name="comment" id="comment" rows="7"></textarea>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12">Start Date <span class="text-danger">*</span>
                                                    <input type="date" name="start_date" id="start_date" class="form-control" value="" >
                                                </div>

                                                <div class="col-md-12 mt-3">End Date
                                                    <input type="date" name="end_date" id="end_date" class="form-control" value=""  >
                                                </div>
                                            </div>
                                        </div>
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
