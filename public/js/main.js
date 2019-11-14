
$(window).on('load',
    function() {
        $('.loader').fadeOut(500);
        $('#submitForm').validate();

        $(function () {
            $('#logout_link').on('click',function (e) {
                e.preventDefault();
                $('#logoutForm').submit();
            });
        });
    });

//$(document).ajaxStart(function() { Pace.restart(); });

function printDoc() {
    window.print();
}


$(function () {
    $('.select2').select2();
    $('#addButton').click(function() {
        $('.myModal').modal('show');
        $('#submitForm')[0].reset();
        $('#id').val(0);
    });



    $('.myModal').on('hidden.bs.modal',
        function(e) {
            $(this).find("#reset").click();
        });

    $('#btn-upload-photo').on('click',
        function() {
            $(this).siblings('#file').trigger('click');
        });

    $(document).find('[data-toggle="tooltip"]').tooltip();

    $('.printBtn').click(function() {
        printDoc();
    });



    $('.datepicker').datepicker({
        autoclose: true,
        todayHighlight: true,
        format:'yyyy-mm-dd'
    });

    $('.clear_text').val("");

    //submit  form
    $(document).on('submit',
        '#submitForm',
        function(e) {
            e.preventDefault();
            var form = $(this);

            if (!form.valid()) return false;

            var button = $("#createBtn");
            var save = $(".btn-save");
            button.button('loading');
            save.button('loading');

            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: form.serialize()
            }).done(function(response) {
                // button loading
                button.button('reset');
                save.button('reset');

                var modal=$('.myModal');
                modal.modal("hide");
                modal.on('hidden.bs.modal',
                    function (e) {
                        $('.loader').fadeIn(500);
                        location.reload();
                    });

            })
                .fail(function (error) {
                    button.button('reset');
                    save.button('reset');
                    $('#add-messages').html('<div class="alert alert-danger">' +
                        '<button type="button" class="close" data-dismiss="alert">&times;</button>' +
                        '<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> ' +
                        "Unable to save record" +
                        '</div>');

                    $(".alert-danger").delay(500).show(10,
                        function() {
                            $(this).delay(3000).hide(10,
                                function() {
                                    $(this).remove();
                                });
                        }); // /.alert
                });
        });


});

$(document).on('click',
    '.js-delete',
    function(e) {
        e.preventDefault();
        var button = $(this);
        var url = button.attr("data-url");
        bootbox.confirm("Are you sure you want to delete this Record?",
            function(result) {
                if (result) {
                    $.ajax({
                        url: url,
                        method: 'DELETE',
                        data:{_token:$('meta[name="csrf-token"]').attr('content')},
                        success: function() {
                            button.parents('tr').remove();
                        }
                    });
                }
            });

    });

function showLoader() {
    // modal spinner
    $('.modal-loading').removeClass('div-hide');
    // modal result
    $('.edit-result').addClass('div-hide');
    //modal footer
    $(".editFooter").addClass('div-hide');
}

function hideLoader() {
    // modal spinner
    $('.modal-loading').addClass('div-hide');
    // modal result
    $('.edit-result').removeClass('div-hide');
    //modal footer
    $(".editFooter").removeClass('div-hide');
}
