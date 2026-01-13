<script>
    $(document).ready(function() {
        $(document).on('change', '.change-status-checkbox', function() {
            var $checkbox = $(this);
            var id = $checkbox.data('id');
            var table = $checkbox.data('table') || '{{@$table}}' || 'users';
            var column = $checkbox.data('column') || '{{@$column}}' || 'status';

            var status = $checkbox.prop('checked') ? 1 : 0;

            $.ajax({
                url: "{{ route('change.status') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    table: table,
                    status: status,
                    column: column,
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                    } else {
                        $checkbox.prop('checked', !$checkbox.prop('checked'));
                        toastr.error(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    $checkbox.prop('checked', !$checkbox.prop('checked'));
                    console.error(error);
                    toastr.error('Something went wrong!');
                }
            });
        });
    });
</script>
