<div id="request_car_modal" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Request New Auto Part</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                    <input type="hidden" name="car_id" value="{{auth()->user()->car->id}}"/>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="col-form-label">Requester <span
                                                class="text-danger">*</span></label>
                                        <input type="text" readonly class="form-control" name="requester"
                                            value="{{ auth()->user()->full_name() }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="col-form-label">Car <span class="text-danger">*</span></label>
                                        <input type="text" name="user" class="form-control" value="{{auth()->user()->car->model}} ({{auth()->user()->car->car_number}})" disabled>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="col-form-label">Request Date <span
                                                class="text-danger">*</span></label>
                                        <input type="date" name="date_needed" class="form-control"
                                            value="{{ today()->toDateString() }}" disabled>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="col-form-label">Request Quantity <span
                                                class="text-danger">*</span></label>
                                        <input type="number" name="qnt_requested" class="form-control"
                                            value="1" step="1" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="col-form-label" for="auto_part_id" >
                                            Auto Part <span class="text-danger">*</span>
                                        </label>
                                        <div class="form-group">
                                            <select id="auto_part_id" name="auto_part_id"
                                                class="select user-search2" style="width: 100%;" required>
                                                <option value="">Select an auto part</option>
                                                @foreach ($autoParts as $part)
                                                    <option value="{{ $part->id }}"
                                                        data-price="{{ $part->unit_cost }}">
                                                        {{ $part->name }}</option>
                                                @endforeach
                                            </select>


                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">Request Purpose <span class="text-danger" required>*</span>
                                        <textarea class="form-control" name="reason_for_request" rows="7"></textarea>
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
