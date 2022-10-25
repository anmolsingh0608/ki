<!doctype html>
<html lang="en">

<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('html/css/css.css') }}">
    <title>Ki Society - Admin</title>
    <style>
        .dropdown-bulk-action {
            color: #000;
            background-color: #f8f9fa;
            border-style: solid;
            border-width: 1px;
            border-color: #EFD4D6;
            width: 100%;
            text-align: left;
        }

        .dropdown-bulk-action:hover {
            color: #000;
            background-color: #f8f9fa;
            border-style: solid;
            border-width: 1px;
            border-color: #EFD4D6;
            width: 100%;
            text-align: left;
        }

        .dropdown-bulk-action-2 {
            color: #000;
            background-color: #f8f9fa;
            border-style: solid;
            border-width: 1px;
            border-color: #EFD4D6;
            width: 100%;
            text-align: left;
        }

        .dropdown-bulk-action-2:hover {
            color: #000;
            background-color: #f8f9fa;
            border-style: solid;
            border-width: 1px;
            border-color: #EFD4D6;
            width: 100%;
            text-align: left;
        }

        .page-item.active>.page-link {
            background-color: #707070;
            border-color: #707070;
        }

        a.page-link {
            color: #707070;
        }

        .anchor {
            color: #0d6efd;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <main class="wrapper">
        <header class="align-items-center d-flex header justify-content-between">
            <div class="align-items-center brand d-flex">
                <img src="{{ asset('images/logo.png') }}" alt=""><span class="text">Ki Society</span>
            </div>
            <div class="navlink">
                <ul class="nav">
                    <li>
                        <a href="{{route('order_cards')}}">Order Cards</a>
                    &nbsp; &nbsp; 
                        <a href="{{route('memberships.index')}}">Membership Cards</a>
                    </li>
                </ul>
            </div>
        </header>
        <section class="ki-admin-wrap">
            <div class="ki-sidebar d-flex flex-column ">
                <div class="member-ship">
                    <a href="{{ route('memberships.index') }}" class="d-flex"><img class="pe-2" src="{{ asset('html/images/home-icon.png') }}" alt="home"> Membership</a>
                </div>
                <ul class="list-unstyled m-0">
                    <li>
                        <a href="{{ route('dojos.index') }}">Dojos</a>
                    </li>
                    <li>
                        <a href="{{ route('dojos.create') }}">Add Dojos</a>
                    </li>
                    <li>
                        <a href="{{ route('organizations.index') }}">Orgs</a>
                    </li>
                    <li>
                        <a href="{{ route('organizations.create') }}">Add Orgs</a>
                    </li>
                    <li>
                        <a href="{{ route('newrequest.index') }}">New Card Requests</a>
                    </li>
                    <li>
                        <a href="{{ route('processed_cards.index') }}">Processed Requests</a>
                    </li>
                    <li>
                        <a href="{{ route('users.index') }}">Admins</a>
                    </li>
                    <li>
                        <a href="{{ route('users.create') }}">Add Admins</a>
                    </li>
                    <li>
                        <a class="" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            Logout
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>

            @yield('content')

        </section>
        <footer class="footer text-center">&copy;2021 Shinshin Toitsu Aikido All rights reserved.</footer>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js" integrity="sha384-skAcpIdS7UcVUC05LJ9Dxay8AXcDYfBJqt1CJ85S/CFujBsIzCIv+l9liuYLaMQ/" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{ asset('js/main.js ') }}"></script>


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
                            $('select[name="sub_organization_id"]').append('<option value="">None</option>');
                            $.each(data, function(key, value) {
                                $('select[name="sub_organization_id"]').append('<option value="' + key + '">' + value + '</option>');
                            });
                        },
                        complete: function() {
                            //$('#loader').css("visibility", "hidden");
                        }
                    });
                } else {
                    $('select[name="sub_organization_id"]').empty();
                }
            });

            $('.dropdown-bulk-action').click(function() {
                if ($('.dropdown-menu-1').is(":visible")) {
                    $('.dropdown-menu-1').hide()
                } else {
                    $('.dropdown-menu-1').show()
                }
            })

            $(document).mouseup(function(e) {
                var container = $(".bAction");

                // if the target of the click isn't the container nor a descendant of the container
                if (!container.is(e.target) && container.has(e.target).length === 0) {
                    $('.dropdown-menu-1').hide();
                }
            });

            $('.dropdown-bulk-action-2').click(function() {
                if ($('.dropdown-menu-2').is(":visible")) {
                    $('.dropdown-menu-2').hide()
                } else {
                    $('.dropdown-menu-2').show()
                }
            })

            $(document).mouseup(function(e) {
                var container = $(".bAction-2");

                // if the target of the click isn't the container nor a descendant of the container
                if (!container.is(e.target) && container.has(e.target).length === 0) {
                    $('.dropdown-menu-2').hide();
                }
            });

            $('#bulk-delete-2').click(function(e) {
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

            $('#bulk-csv').click(function(e) {
                e.preventDefault();

                var allVals = [];
                $(".singlechkbox:checked").each(function() {
                    allVals.push($(this).attr('data-id'));
                });


                if (allVals.length <= 0) {
                    alert("Please select row.");
                } else {
                    var check = confirm("Are you sure you want to export this row to CSV?");

                    if (check == true) {
                        var join_selected_values = allVals.join(",");
                        $.ajax({
                            url: $(this).data('uri'),
                            type: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: 'ids=' + join_selected_values,
                            success: function(data) {
                                if (data) {
                                    window.location.href = $('#bulk-csv').data('uri') + '?ids=' + join_selected_values;
                                    // window.location.reload();

                                } else {
                                    console.log(data);
                                    alert('Whoops Something went wrong!!');
                                }
                            },
                            error: function(data) {
                                window.location.reload();
                            },
                            complete: function() {
                                // setTimeout(function(){
                                //     window.location.reload();
                                //     $('input:checkbox:checked').prop('checked', false);
                                // }, 2000);
                            }
                        });
                    }
                }
            });


            $('#bulk-csv-2').click(function(e) {
                e.preventDefault();

                var allVals = [];
                $(".singlechkbox:checked").each(function() {
                    allVals.push($(this).attr('data-id'));
                });


                if (allVals.length <= 0) {
                    alert("Please select row.");
                } else {
                    var check = confirm("Are you sure you want to export this row to CSV?");

                    if (check == true) {
                        var join_selected_values = allVals.join(",");
                        $.ajax({
                            url: $(this).data('uri'),
                            type: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: 'ids=' + join_selected_values,
                            success: function(data) {
                                if (data) {
                                    window.location.href = $('#bulk-csv').data('uri') + '?ids=' + join_selected_values;
                                    console.log(join_selected_values);
                                } else {
                                    alert('Whoops Something went wrong!!');
                                }
                            },
                            error: function(data) {
                                window.location.reload();
                            },
                            complete: function() {
                                // setTimeout(function(){
                                //     window.location.reload();
                                //     $('input:checkbox:checked').prop('checked', false);
                                // }, 2000);
                            }
                        });
                    }
                }
            });

            $('#bulk-csv-Bodno').click(function(e) {
                e.preventDefault();

                var allVals = [];
                $(".singlechkbox:checked").each(function() {
                    allVals.push($(this).attr('data-id'));
                });


                if (allVals.length <= 0) {
                    alert("Please select row.");
                } else {
                    var check = confirm("Are you sure you want to export this row to CSV?");

                    if (check == true) {
                        var join_selected_values = allVals.join(",");
                        $.ajax({
                            url: $(this).data('uri'),
                            type: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: 'ids=' + join_selected_values,
                            success: function(data) {
                                if (data) {
                                    window.location.href = $('#bulk-csv-Bodno').data('uri') + '?ids=' + join_selected_values;
                                    console.log(join_selected_values);
                                } else {
                                    alert('Whoops Something went wrong!!');
                                }
                            },
                            error: function(data) {
                                window.location.reload();
                            },
                            complete: function() {
                                // setTimeout(function(){
                                //     window.location.reload();
                                //     $('input:checkbox:checked').prop('checked', false);
                                // }, 2000);
                            }
                        });
                    }
                }
            });

            $('#bulk-csv-K12').click(function(e) {
                e.preventDefault();

                var allVals = [];
                $(".singlechkbox:checked").each(function() {
                    allVals.push($(this).attr('data-id'));
                });


                if (allVals.length <= 0) {
                    alert("Please select row.");
                } else {
                    var check = confirm("Are you sure you want to export this row to CSV?");

                    if (check == true) {
                        var join_selected_values = allVals.join(",");
                        $.ajax({
                            url: $(this).data('uri'),
                            type: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: 'ids=' + join_selected_values,
                            success: function(data) {
                                if (data) {
                                    window.location.href = $('#bulk-csv-K12').data('uri') + '?ids=' + join_selected_values;
                                    console.log(join_selected_values);
                                } else {
                                    alert('Whoops Something went wrong!!');
                                }
                            },
                            error: function(data) {
                                window.location.reload();
                            },
                            complete: function() {
                                // setTimeout(function(){
                                //     window.location.reload();
                                //     $('input:checkbox:checked').prop('checked', false);
                                // }, 2000);
                            }
                        });
                    }
                }
            });

            $('#bulk-csv-Bodno-2').click(function(e) {
                e.preventDefault();

                var allVals = [];
                $(".singlechkbox:checked").each(function() {
                    allVals.push($(this).attr('data-id'));
                });


                if (allVals.length <= 0) {
                    alert("Please select row.");
                } else {
                    var check = confirm("Are you sure you want to export this row to CSV?");

                    if (check == true) {
                        var join_selected_values = allVals.join(",");
                        $.ajax({
                            url: $(this).data('uri'),
                            type: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: 'ids=' + join_selected_values,
                            success: function(data) {
                                if (data) {
                                    window.location.href = $('#bulk-csv-Bodno').data('uri') + '?ids=' + join_selected_values;
                                    console.log(join_selected_values);
                                } else {
                                    alert('Whoops Something went wrong!!');
                                }
                            },
                            error: function(data) {
                                window.location.reload();
                            },
                            complete: function() {
                                // setTimeout(function(){
                                //     window.location.reload();
                                //     $('input:checkbox:checked').prop('checked', false);
                                // }, 2000);
                            }
                        });
                    }
                }
            });

            $('#bulk-csv-K12-2').click(function(e) {
                e.preventDefault();

                var allVals = [];
                $(".singlechkbox:checked").each(function() {
                    allVals.push($(this).attr('data-id'));
                });


                if (allVals.length <= 0) {
                    alert("Please select row.");
                } else {
                    var check = confirm("Are you sure you want to export this row to CSV?");

                    if (check == true) {
                        var join_selected_values = allVals.join(",");
                        $.ajax({
                            url: $(this).data('uri'),
                            type: 'GET',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: 'ids=' + join_selected_values,
                            success: function(data) {
                                if (data) {
                                    window.location.href = $('#bulk-csv-K12').data('uri') + '?ids=' + join_selected_values;
                                    console.log(join_selected_values);
                                } else {
                                    alert('Whoops Something went wrong!!');
                                }
                            },
                            error: function(data) {
                                window.location.reload();
                            },
                            complete: function() {
                                // setTimeout(function(){
                                //     window.location.reload();
                                //     $('input:checkbox:checked').prop('checked', false);
                                // }, 2000);
                            }
                        });
                    }
                }
            });
        });
    </script>
</body>

</html>