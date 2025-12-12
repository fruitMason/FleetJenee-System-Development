<div id="add_accident_modal" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Accident</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal_body">
                <form method="post" id="form_create" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="col-form-label">User <span class="text-danger">*</span></label>
                                        <select class="form-control create_select_search" name="user_id" required>
                                            <option value="0">-- select user/driver --</option>
                                            @foreach($users as $user)
                                                <option value="{{$user->id}}">{{$user->full_name()}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="col-form-label">Car <span class="text-danger">*</span></label>
                                        <select class="form-control create_select_search" name="car_id" required>
                                            <option value="0">-- select car --</option>
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
                                        <label class="col-form-label">Date Reported <span class="text-danger">*</span></label>
                                        <input type="datetime-local" name="date_reported" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="col-form-label">Location <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="location" rows="7"></textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="col-form-label">Description </label>
                                        <textarea class="form-control" name="description" rows="7"></textarea>
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
