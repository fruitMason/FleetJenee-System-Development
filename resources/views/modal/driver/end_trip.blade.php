<!-- End Trip Modal -->
<div id="end_trip_modal" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">End ELog Trip</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal_body">
                <form id="end_trip_form" method="post">
                    @csrf
                    <input type="hidden" id="end_trip_id" name="id">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-form-label">End Odometer <span class="text-danger">*</span></label>
                                <input type="number" placeholder="Enter the End Odometer" id="end_odometer" name="end_odometer" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <div class="submit-section">
                                    <button type="button" class="btn btn-primary submit-btn" onclick="submitEndTrip()">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
