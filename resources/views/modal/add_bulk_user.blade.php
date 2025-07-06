<div id="add_bulk_user_modal" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Upload Users</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form  method="post" action="{{route('settings.users.bulk')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">

                                <div class="file-upload">
                                    <a class="file-upload-btn" style="text-align: center; align-items: center;" type="button" href="{{asset('templates/goil_user_import.xlsx')}}" onclick="">Download Template</a>

                                    <div class="image-upload-wrap">
                                        <input class="form-control-file" id="file" name="file" type='file' accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
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
