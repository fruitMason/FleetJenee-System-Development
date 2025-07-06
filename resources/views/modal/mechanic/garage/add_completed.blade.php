<div id="add_completed_modal" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Completed</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal_body">
                <form method="post" action="{{route('mechanic.garage.confirm.completed')}}" class="confirmationForm" enctype="multipart/form-data">
                    <input type="hidden" name="car_id_completed" id="car_id_completed">
                    <input type="hidden" name="id_completed" id="id_completed">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-form-label">Car <span class="text-danger">*</span></label>
                                        <select class="form-control select" name="car_completed" id="car_completed" required disabled>
                                            <option>-- select car --</option>
                                            @foreach($cars as $car)
                                                <option value="{{$car->id}}">{{$car->model}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-8">Completed Comment <span class="text-danger">*</span>
                                        <textarea class="form-control" name="completed_comment" id="completed_comment" rows="7"></textarea>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12">Date Completed <span class="text-danger">*</span>
                                                    <input type="date" name="completed_date" id="completed_date" class="form-control" value="" >
                                                </div>
                                            </div>
                                        </div>
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
