<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Ki Society') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/css.css') }}">
    <style>
        .hidden {
            display: none;
        }
    </style>
</head>

<body>
    <main class="wrapper">
        <header class="align-items-center d-flex header justify-content-between">
            <div class="align-items-center brand d-flex">
                <a href="/"><img src="/images/logo.png" alt=""></a><span class="text">Ki Society</span>
            </div>
            <div class="navlink">
                <ul class="nav">
                    <li>
                    <a href="{{route('order_cards')}}">Order Cards</a> &nbsp; &nbsp; 
                    <a href="https://forms.gle/dkUibUbZzqPpJzdE8" target="_blank">Submit Dojo</a> &nbsp; &nbsp; 
                    <a href="https://forms.gle/WwsdrbbPNocmcGow7" target="_blank">Submit Org</a>&nbsp; &nbsp; 
                    <a href="/login">Admin</a>
                    </li>
                </ul>
            </div>
        </header>

        @yield('content')

        <footer class="footer text-center">&copy;2021 Shinshin Toitsu Aikido All rights reserved.</footer>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js" integrity="sha384-skAcpIdS7UcVUC05LJ9Dxay8AXcDYfBJqt1CJ85S/CFujBsIzCIv+l9liuYLaMQ/" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('body').on('click', '#selectall', function() {
                $('.singlechkbox').prop('checked', this.checked);
            });

            $('body').on('click', '.singlechkbox', function() {
                if ($('.singlechkbox').length == $('.singlechkbox:checked').length) {
                    $('#selectall').prop('checked', true);
                } else {
                    $("#selectall").prop('checked', false);
                }
            });

            $('#bulk-delete').click(function(e) {
                e.preventDefault();

                var allVals = [];
                $(".singlechkbox:checked").each(function() {
                    allVals.push($(this).attr('data-id'));
                });


                if (allVals.length <= 0) {
                    alert("Please select row.");
                } else {
                    var check = confirm("Are you sure you want to delete this row?");

                    if (check == true) {
                        var join_selected_values = allVals.join(",");
                        $.ajax({
                            url: $(this).data('uri'),
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: 'ids=' + join_selected_values,
                            success: function(data) {
                                if (data['success']) {
                                    window.location.reload();
                                } else {
                                    alert('Whoops Something went wrong!!');
                                }
                            },
                            error: function(data) {
                                window.location.reload();
                            }
                        });
                    }
                }
            });

            $('select[name="organization_id"]').on('change', function() {
                var organization_id = $(this).val();
                if (organization_id) {
                    $.ajax({
                        url: '/getSubOrganizations/' + organization_id,
                        type: "GET",
                        dataType: "json",
                        beforeSend: function() {
                            //$('#loader').css("visibility", "visible");
                            $('select[name="sub_organization_id"]').empty();
                        },
                        success: function(data) {
                            $('select[name="sub_organization_id"]').empty();
                            $('select[name="sub_organization_id"]').append("<option value=''>None</option>");
                            $.each(data, function(key, value) {
                                $('select[name="sub_organization_id"]').append('<option value="' + key + '">' + value + '</option>');
                            });
                        },
                        complete: function() {
                            //$('#loader').css("visibility", "hidden");
                        }
                    });
                    setTimeout(function() {
                        if ($('select[name="sub_organization_id"]').val() == '') {
                            var sub_org_id = null;
                        } else {
                            var sub_org_id = $('select[name="sub_organization_id"]').val();
                        }
                        // console.log('/getDojos/' + organization_id + '/sub_org/' + sub_org_id);
                        $.ajax({
                            url: '/getDojos/' + organization_id + '/sub_org/' + sub_org_id,
                            type: "GET",
                            dataType: "json",
                            beforeSend: function() {
                                //$('#loader').css("visibility", "visible");
                                $('select[name="ship_to"]').empty();
                                $('select[name="invoice_to"]').empty();
                            },
                            success: function(data) {
                                $('select[name="ship_to"]').empty();
                                $('select[name="invoice_to"]').empty();
                                $.each(data, function(key, value) {
                                    $('select[name="ship_to"]').append('<option value="' + value + '">' + value + '</option>');
                                    $('select[name="invoice_to"]').append('<option value="' + value + '">' + value + '</option>');
                                });
                            },
                            complete: function() {
                                //$('#loader').css("visibility", "hidden");
                            }
                        });

                        // $.ajax({
                        //     url: '/getInvoice/' + organization_id + '/sub_org/' + sub_org_id,
                        //     type: "GET",
                        //     dataType: "json",
                        //     beforeSend: function() {
                        //         //$('#loader').css("visibility", "visible");
                        //         $('select[name="invoice_to"]').empty();
                        //     },
                        //     success: function(data) {
                        //         $('select[name="invoice_to"]').empty();
                        //         $.each(data, function(key, value) {
                        //             $('select[name="invoice_to"]').append('<option value="' + key + '">' + value + '</option>');
                        //         });
                        //     },
                        //     complete: function() {
                        //         //$('#loader').css("visibility", "hidden");
                        //     }
                        // });
                    }, 500);
                } else {
                    $('select[name="sub_organization_id"]').empty();
                }
                let val = $('#organization_id').find(':selected').val();
                $('#organization_id_card option[value="'+val+'"]').attr('selected', 'selected');
                $('#organization_id_card').trigger('change');
            });

            $('select[name="sub_organization_id"]').on('change', function() {
                if ($(this).val() == '') {
                    var sub_org_id = null;
                } else {
                    var sub_org_id = $(this).val();
                }
                var org_id = $('select[name="organization_id"]').val();
                // console.log('/getDojos/' + organization_id + '/sub_org/' + sub_org_id);
                $.ajax({
                    url: '/getDojos/' + org_id + '/sub_org/' + sub_org_id,
                    type: "GET",
                    dataType: "json",
                    beforeSend: function() {
                        //$('#loader').css("visibility", "visible");
                        $('select[name="ship_to"]').empty();
                        $('select[name="invoice_to"]').empty();
                    },
                    success: function(data) {
                        $('select[name="ship_to"]').empty();
                        $('select[name="invoice_to"]').empty();
                        $.each(data, function(key, value) {
                            $('select[name="ship_to"]').append('<option value="' + value + '">' + value + '</option>');
                            $('select[name="invoice_to"]').append('<option value="' + value + '">' + value + '</option>');
                        });
                    },
                    complete: function() {
                        //$('#loader').css("visibility", "hidden");
                    }
                });
                let _val = $('#sub_organization_id').find(':selected').val();
                $('#sub_organization_id_card option[value="'+_val+'"]').attr('selected', 'selected');
            });

            $('select[name="organization_id_card"]').on('change', function() {
                var organization_id = $(this).val();
                if (organization_id) {
                    $.ajax({
                        url: '/getSubOrganizations/' + organization_id,
                        type: "GET",
                        dataType: "json",
                        beforeSend: function() {
                            //$('#loader').css("visibility", "visible");
                            $('select[name="sub_organization_id_card"]').empty();
                        },
                        success: function(data) {
                            $('select[name="sub_organization_id_card"]').empty();
                            $('select[name="sub_organization_id_card"]').append("<option value=''>None</option>");
                            $.each(data, function(key, value) {
                                $('select[name="sub_organization_id_card"]').append('<option value="' + key + '">' + value + '</option>');
                            });
                        },
                        complete: function() {
                            //$('#loader').css("visibility", "hidden");
                        }
                    });
                    setTimeout(function() {
                        if ($('select[name="sub_organization_id_card"]').val() == '') {
                            var sub_org_id = null;
                        } else {
                            var sub_org_id = $('select[name="sub_organization_id_card"]').val();
                        }
                        // console.log('/getDojos/' + organization_id + '/sub_org/' + sub_org_id);
                        $.ajax({
                            url: '/getDojosOrg/' + organization_id + '/sub_org/' + sub_org_id,
                            type: "GET",
                            dataType: "json",
                            beforeSend: function() {
                                //$('#loader').css("visibility", "visible");
                                $('select[name="dojo_id"]').empty();
                                // $('select[name="invoice_to"]').empty();
                            },
                            success: function(data) {
                                $('select[name="dojo_id"]').empty();
                                $('select[name="dojo_id"]').append("<option value=''>None</option>");
                                // $('select[name="invoice_to"]').empty();
                                $.each(data, function(key, value) {
                                    $('select[name="dojo_id"]').append('<option value="' + key + '">' + value + '</option>');
                                    // $('select[name="invoice_to"]').append('<option value="' + value + '">' + value + '</option>');
                                });
                            },
                            complete: function() {
                                //$('#loader').css("visibility", "hidden");
                            }
                        });

                    }, 500);
                } else {
                    $('select[name="sub_organization_id_card"]').empty();
                }
            });

            $('select[name="sub_organization_id_card"]').on('change', function() {
                if ($(this).val() == '') {
                    var sub_org_id = null;
                } else {
                    var sub_org_id = $(this).val();
                }
                var org_id = $('select[name="organization_id_card"]').val();
                // console.log('/getDojos/' + organization_id + '/sub_org/' + sub_org_id);
                $.ajax({
                    url: '/getDojosOrg/' + org_id + '/sub_org/' + sub_org_id,
                    type: "GET",
                    dataType: "json",
                    beforeSend: function() {
                        //$('#loader').css("visibility", "visible");
                        $('select[name="dojo_id"]').empty();
                        // $('select[name="invoice_to"]').empty();
                    },
                    success: function(data) {
                        $('select[name="dojo_id"]').empty();
                        $('select[name="dojo_id"]').append("<option value=''>None</option>");
                        // $('select[name="invoice_to"]').empty();
                        $.each(data, function(key, value) {
                            $('select[name="dojo_id"]').append('<option value="' + key + '">' + value + '</option>');
                            // $('select[name="invoice_to"]').append('<option value="' + value + '">' + value + '</option>');
                        });
                    },
                    complete: function() {
                        //$('#loader').css("visibility", "hidden");
                    }
                });

            });

            $('select[name="new_organization_id_card"]').on('change', function() {
                var organization_id = $(this).val();
                if (organization_id) {
                    $.ajax({
                        url: '/getSubOrganizations/' + organization_id,
                        type: "GET",
                        dataType: "json",
                        beforeSend: function() {
                            //$('#loader').css("visibility", "visible");
                            $('select[name="new_sub_organization_id_card"]').empty();
                        },
                        success: function(data) {
                            $('select[name="new_sub_organization_id_card"]').empty();
                            $('select[name="new_sub_organization_id_card"]').append("<option value=''>None</option>");
                            $.each(data, function(key, value) {
                                $('select[name="new_sub_organization_id_card"]').append('<option value="' + key + '">' + value + '</option>');
                            });
                        },
                        complete: function() {
                            //$('#loader').css("visibility", "hidden");
                        }
                    });
                    setTimeout(function() {
                        if ($('select[name="new_sub_organization_id_card"]').val() == '') {
                            var sub_org_id = null;
                        } else {
                            var sub_org_id = $('select[name="new_sub_organization_id_card"]').val();
                        }
                        // console.log('/getDojos/' + organization_id + '/sub_org/' + sub_org_id);
                        $.ajax({
                            url: '/getDojosOrg/' + organization_id + '/sub_org/' + sub_org_id,
                            type: "GET",
                            dataType: "json",
                            beforeSend: function() {
                                //$('#loader').css("visibility", "visible");
                                $('select[name="new_dojo_id"]').empty();
                                // $('select[name="invoice_to"]').empty();
                            },
                            success: function(data) {
                                $('select[name="new_dojo_id"]').empty();
                                $('select[name="new_dojo_id"]').append("<option value=''>None</option>");
                                // $('select[name="invoice_to"]').empty();
                                $.each(data, function(key, value) {
                                    $('select[name="new_dojo_id"]').append('<option value="' + key + '">' + value + '</option>');
                                    // $('select[name="invoice_to"]').append('<option value="' + value + '">' + value + '</option>');
                                });
                            },
                            complete: function() {
                                //$('#loader').css("visibility", "hidden");
                            }
                        });

                    }, 500);
                } else {
                    $('select[name="new_sub_organization_id_card"]').empty();
                }
            });

            $('select[name="new_sub_organization_id_card"]').on('change', function() {
                if ($(this).val() == '') {
                    var sub_org_id = null;
                } else {
                    var sub_org_id = $(this).val();
                }
                var org_id = $('select[name="new_organization_id_card"]').val();
                // console.log('/getDojos/' + organization_id + '/sub_org/' + sub_org_id);
                $.ajax({
                    url: '/getDojosOrg/' + org_id + '/sub_org/' + sub_org_id,
                    type: "GET",
                    dataType: "json",
                    beforeSend: function() {
                        //$('#loader').css("visibility", "visible");
                        $('select[name="new_dojo_id"]').empty();
                        // $('select[name="invoice_to"]').empty();
                    },
                    success: function(data) {
                        $('select[name="new_dojo_id"]').empty();
                        $('select[name="new_dojo_id"]').append("<option value=''>None</option>");
                        // $('select[name="invoice_to"]').empty();
                        $.each(data, function(key, value) {
                            $('select[name="new_dojo_id"]').append('<option value="' + key + '">' + value + '</option>');
                            // $('select[name="invoice_to"]').append('<option value="' + value + '">' + value + '</option>');
                        });
                    },
                    complete: function() {
                        //$('#loader').css("visibility", "hidden");
                    }
                });

            });
            

            $('.add-card').click(function() {
                $('.add-card-form').removeClass('hidden');
                $('.card-list').addClass('hidden');
            });
            // $('.show-list').click(function(){
            //     $('.add-card-form').addClass('hidden');
            //     $('.card-list').removeClass('hidden');
            // });
            submitForms = function() {
                document.getElementsByClassName('membership').submit();
                document.getElementsByClassName('orders').submit();
            }
            // $('#order-button').click( function(){
            //     $.ajax({
            //         url: '/orders/store',
            //         type: 'POST',
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         }
            //         success: function(data) {
            //             if (data['success']) {
            //                 window.location.reload();
            //             } else {
            //                 alert('Whoops Something went wrong!!');
            //             }
            //         },
            //         error: function(data) {
            //             window.location.reload();
            //         }
            //     });
            // });

            $('#order-button').click(function() {
                $('.final-order').submit();
            });
            $('.card-list-item-delte').click(function() {
                $(' #card-list-item-id ').val($(this).attr('data-id'));
                $(' .card-list-delete ').submit();
                // $.ajax({
                //             url: '/',
                //             type: 'DELETE',
                //             headers: {
                //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                //             },
                //             data: 'id='+$(this).attr('data-id'),
                //             success: function(data) {
                //                 if (data['success']) {
                //                     window.location.reload();
                //                 } else {
                //                     alert('Whoops Something went wrong!!');
                //                     console.log(data);
                //                 }
                //             },
                //             error: function(data) {
                //                 window.location.reload();
                //             }
                //         });
            });
            $('.card-list-item-edit').click(function() {
                $('#edit-list-item-id').val($(this).attr('data-id'));
                $('.card-list-edit').submit();
            });
            $('.tab-large-order').click(function() {
                $('.tab-small-order').removeClass('active');
                $('.tab-large-order').addClass('active');
                $('.large-order').removeClass('hidden');
                $('.small-order').addClass('hidden');
                $('.add-card-form').addClass('hidden');
                $('.add-card-form .small-order-input').removeAttr('required');
                $('.lOrder').attr('required', '');
            });
            $('.tab-small-order').click(function() {
                $('.tab-large-order').removeClass('active');
                $('.tab-small-order').addClass('active');
                $('.large-order').addClass('hidden');
                $('.small-order').removeClass('hidden');
                $('.add-card-form .small-order-input').attr('required', '');
                $('.lOrder').removeAttr('required');
            });
            if ($('.confirmation-large-order').length > 0) {
                $('.tab-large-order').click();
            }
        });
    </script>
</body>

</html>