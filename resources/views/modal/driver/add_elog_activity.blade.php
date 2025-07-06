<div id="update_elog_modal" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update ELog</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal_body">
                <form method="post" enctype="multipart/form-data" action="{{route('driver.report.elog.update')}}">
                    <input type="hidden" name="id" value="{{request()->segment(count(request()->segments()))}}"/>
                    <input type="hidden" name="user_id" value="{{auth()->id()}}"/>
                    <input type="hidden" name="car_id" value="{{auth()->user()->car->id}}"/>
                    @csrf
                    <div class="row">
                        <div class="col-md-12">

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="col-form-label">User <span class="text-danger">*</span></label>
                                        <input type="text" name="user" class="form-control" value="{{auth()->user()->full_name()}}" disabled>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="col-form-label">Car <span class="text-danger">*</span></label>
                                        <input type="text" name="user" class="form-control" value="{{auth()->user()->car->model}} ({{auth()->user()->car->car_number}})" disabled>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-form-label">Date Logged <span class="text-danger">*</span></label>
                                        <input type="datetime-local" name="date_logged" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="col-form-label">Current Location </label>
                                        <textarea class="form-control" name="current_location" rows="7"></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="col-form-label">Destination <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="destination" rows="7"></textarea>
                                    </div>
                                </div>

                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-form-label">Media (Audio, Videos, Images) </label>
                                        <div class="image-upload-wrap">
                                            <input class="form-control-file" id="file" name="file" type='file' accept="image/*, audio/*, video/*, .csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-form-label">Other Information</label>
                                        <textarea class="form-control" name="description" rows="7"></textarea>
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
