<div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom-bg-gradient-info">
                <h5 class="modal-title">
                    <i class="fas fa-trash"></i> Are you sure?
                </h5>
            </div>
            <div class="modal-body" id="delete_model_body">
                This action is permanent.
            </div>
            <div class="modal-footer">
                <form action="#" id="delete_form" method="POST">
                    {{ method_field("DELETE") }}
                    {{ csrf_field() }}
                    <input type="submit" class="btn btn-danger pull-right delete-confirm"
                           value="{{ __('Confirm') }}">
                </form>
            </div>
        </div>
    </div>
</div>
