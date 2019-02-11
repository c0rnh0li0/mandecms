
<script>
    window.onload = function () {
        $('.toast').each(function (i, toast) {
            var message = $(toast).find('p').html();
            toastr.options = {
                "closeButton": true,
                "preventDuplicates": true,
                "timeOut": 3000,
                "newestOnTop": true,
            };

            if ($(toast).hasClass('toast-error'))
                toastr.error(message);
            if ($(toast).hasClass('toast-success'))
                toastr.success(message);
            else
                toastr.info(message);
        });

        if ($('.datatable').length > 0) {
                var table = $('.datatable').DataTable({
                    serverSide: true,
                    ajax: {
                        url: '/users/userslist',
                        type: 'GET',
                        dataSrc: "data",
                        /*
                        "data": function ( d ) {
                         return $.extend( {}, d, {
                         "extra_search": $('#extra').val()
                         } );
                         }*/
                    },/*
                    columns: [{
                            title: "id"
                        },
                        {
                            title: "name"
                        },
                        {
                            title: "email"
                        },
                        {
                            title: "user_role"
                        },
                        {
                            title: "user_avatar"
                        },
                        {
                            title: "created_at"
                        }
                    ],*/

                    dom: 'Bfrtip',
                    select: false,
                });
        }
    };
</script>