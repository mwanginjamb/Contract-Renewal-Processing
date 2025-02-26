$(document).ready(function () {
    // Load the form when modal is shown
    $('#templateLineModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var url = button.data('form-url');
        formID = button.data('form-id');
        modelID = button.data('model-id');

        $(this).data('form-id', formID);
        $(this).data('model-id', modelID);

        $.ajax({
            url: url,
            type: 'GET',
            success: function (response) {
                $('#templateLineFormContainer').html(response);
            },
            error: function (e) {
                console.log(e);
                $('#templateLineFormContainer').html('<div class="alert alert-danger">Error loading form</div>');
            }
        });
    });

    // Clear the form when modal is hidden
    $('#templateLineModal').on('hidden.bs.modal', function () {
        $('#templateLineFormContainer').html('');
        location.reload();
    });

    // Handle form submission via AJAX
    $('#templateLineFormContainer form').on('submit', function (e) {
        e.preventDefault();
        // Get the current active modal
        var modal = $('#templateLineModal');
        // Get the form ID from the modal
        var formId = modal.data('form-id');
        var modelID = modal.data('model-id')

        console.log(`Target id: ${e.target.id}`);
        console.log(`Form id: ${formId}`);
        // Get the form data and append the parent ID
        var formData = $(this).serialize();
        formData += '&model_id=' + modelID;

        console.log(formData);
        if (e.target.id === formId) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                success: function (response) {
                    if (response.success) {
                        // Close modal
                        $('#templateLineModal').modal('hide');

                        // You might want to refresh your main view here
                        // or update it with the newly created item

                        // Show success message
                        alert('Template line created successfully!');
                    } else {
                        // If there are validation errors, they will be rendered in the form
                        $('#templateLineFormContainer').html(response);
                    }
                }
            });

        }
    });
});