<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ ucfirst(AppSettings::get('app_name', 'App')) }} - {{ ucfirst($title ?? '') }}</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" 
          href="{{ !empty(AppSettings::get('favicon')) ? secure_asset('storage/' . AppSettings::get('favicon')) : secure_asset('assets/img/favicon.png') }}">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ secure_asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('assets/css/feathericon.min.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('assets/css/icons.min.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('assets/plugins/snackbar/snackbar.min.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ secure_asset('assets/css/style.css') }}">
    
    <!-- Page Specific CSS -->
    @stack('page-css')

    <!--[if lt IE 9]>
        <script src="{{ secure_asset('assets/js/html5shiv.min.js') }}"></script>
        <script src="{{ secure_asset('assets/js/respond.min.js') }}"></script>
    <![endif]-->
</head>
<body>

    <!-- Main Wrapper -->
    <div class="main-wrapper">

        <!-- Header -->
        @include('admin.includes.header')

        <!-- Sidebar -->
        @include('admin.includes.sidebar')

        <!-- Page Wrapper -->
        <div class="page-wrapper">
            <div class="content container-fluid">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="row">
                        @stack('page-header')
                    </div>
                </div>
                <!-- /Page Header -->

                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <x-alerts.danger :error="$error" />
                    @endforeach
                @endif

                @yield('content')

                <!-- Add Sales Modal -->
                <x-modals.add-sale />
                <!-- /Add Sales Modal -->
            </div>
        </div>
        <!-- /Page Wrapper -->

    </div>
    <!-- /Main Wrapper -->
    
</body>

<!-- Scripts -->
<script src="{{ secure_asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ secure_asset('assets/js/popper.min.js') }}"></script>
<script src="{{ secure_asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ secure_asset('assets/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ secure_asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ secure_asset('assets/plugins/snackbar/snackbar.min.js') }}"></script>
<script src="{{ secure_asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ secure_asset('assets/js/script.js') }}"></script>

<script>
    $(document).ready(function(){
        $('body').on('click','#deletebtn',function(){
            var id = $(this).data('id');
            var route = $(this).data('route');
            swal.queue([
                {
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonText: '<i class="fe fe-trash mr-1"></i> Delete!',
                    cancelButtonText: '<i class="fa fa-times mr-1"></i> Cancel!',
                    confirmButtonClass: "btn btn-success mt-2",
                    cancelButtonClass: "btn btn-danger ml-2 mt-2",
                    buttonsStyling: false,
                    preConfirm: function(){
                        return new Promise(function(){
                            $.ajax({
                                url: route,
                                type: "DELETE",
                                data: {"id": id},
                                success: function(){
                                    swal.insertQueueStep(
                                        Swal.fire({
                                            title: "Deleted!",
                                            text: "Resource has been deleted.",
                                            type: "success",
                                            showConfirmButton: false,
                                            timer: 1500,
                                        })
                                    )
                                    $('.datatable').DataTable().ajax.reload();
                                }
                            })

                        })
                    }
                }
            ]).catch(swal.noop);
        }); 
    });

    @if(Session::has('message'))
        var type = "{{ Session::get('alert-type', 'info') }}";
        switch(type){
            case 'info':
                Snackbar.show({
                    text: "{{ Session::get('message') }}",
                    actionTextColor: '#fff',
                    backgroundColor: '#2196f3'
                });
                break;
            case 'warning':
                Snackbar.show({
                    text: "{{ Session::get('message') }}",
                    pos: 'top-right',
                    actionTextColor: '#fff',
                    backgroundColor: '#e2a03f'
                });
                break;
            case 'success':
                Snackbar.show({
                    text: "{{ Session::get('message') }}",
                    pos: 'top-right',
                    actionTextColor: '#fff',
                    backgroundColor: '#8dbf42'
                });
                break;
            case 'danger':
                Snackbar.show({
                    text: "{{ Session::get('message') }}",
                    pos: 'top-right',
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a'
                });
                break;
        }
    @endif
</script>

<!-- Page Specific Scripts -->
@stack('page-js')

</html>
