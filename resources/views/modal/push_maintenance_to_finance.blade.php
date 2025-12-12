<div id="push_maintenance_to_finance" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Notify Account To Pay</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal_body">
                <form method="post" id="form_create" action="{{ route('finance.invoice.submittofinance') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-form-label">Vendor </label>
                                <input type="text" class="form-control" id="vendor" disabled>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="tid" name="tid">


                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="col-form-label">Invoice #
                                </label>
                                <input type="text" class="form-control" id="invoice" disabled>
                            </div>

                            <div class="col-md-6">
                                <label class="col-form-label">Amount </label>
                                <input type="text" class="form-control" id="total" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="for_user_id" class="d-block mb-2"> In Favor Of <span class="text-danger">*</span></label>

                                <select class="select floating user-search2" style="width: 100%;" name="for_user_id" required>
                                    <option value="">Select User</option>
                                    @foreach ($users as $user)
                                        @if (old('for_user_id') == $user->id)
                                            <option value="{{ $user->id }}" selected>
                                                {{ str_replace('  ', ' ', ucwords($user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name . ' - ' . $user->type)) }}
                                            </option>
                                        @else
                                            <option value="{{ $user->id }}">
                                                {{ str_replace('  ', ' ', ucwords($user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name . ' - ' . $user->type)) }}
                                            </option>
                                        @endif
                                    @endforeach


                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
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
