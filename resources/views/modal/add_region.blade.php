<div id="add_region_modal" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Region</h5>
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
                                    <div class="col-md-6">
                                        <label class="col-form-label">Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" value="" placeholder="Enter Region Name">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="col-form-label">Zone <span class="text-danger">*</span></label>
                                        <select class="form-control select" name="sector_id" required>
                                            <option>-- select zone --</option>
                                            @foreach($sectors as $sector)
                                                <option value="{{$sector->id}}">{{$sector->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">Description
                                        <textarea class="form-control" name="description" rows="7"></textarea>
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
