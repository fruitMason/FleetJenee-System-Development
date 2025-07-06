<div id="update_invoice_status_modal" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Invoice Status</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal_body">
                <form method="post" id="form_create" action="{{route('finance.invoice.status.update')}}">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-form-label">Invoice <span class="text-danger">*</span></label>
                                        <select class="form-control create_select_search" name="id" id="id" required>
                                            <option value="0">-- select invoice --</option>
                                            @foreach($invoices as $invoice)
                                                <option value="{{$invoice->id}}">{{$invoice->invoice_number}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-form-label">Status <span class="text-danger">*</span></label>
                                        <select class="form-control create_select_search" name="status" required>
                                            <option value="pending">Pending</option>
                                            <option value="paid">Paid (Full)</option>
                                            <option value="partially_paid">Paid (Partial)</option>
                                        </select>
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
