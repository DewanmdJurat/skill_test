<!doctype html>
<html class="fixed">
    <head>

        <!-- Basic -->
        <meta charset="UTF-8">

        <title>Development Test</title>
        <meta name="keywords" content="" />
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <!-- Mobile Metas -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

        <!-- Web Fonts  -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

        <!-- Vendor CSS -->
        <link rel="stylesheet" href="{{asset('assets/vendor/bootstrap/css/bootstrap.css')}}" />

        <link rel="stylesheet" href="{{asset('assets/vendor/font-awesome/css/all.min.css')}}" />
        <!-- Specific Page Vendor CSS -->
        <link rel="stylesheet" href="{{asset('assets/vendor/toastr/toastr.min.css')}}" />
        <!-- Theme CSS -->
        <link rel="stylesheet" href="{{asset('assets/css/theme.css')}}" />

        <!-- Skin CSS -->
        <link rel="stylesheet" href="{{asset('assets/css/skins/default.css')}}" />
    </head>
    <body>
        <section class="body">

            <!-- start: header -->
            @include('priv.inc.header')
            <!-- end: header -->

            <div class="inner-wrapper">
                <!-- start: sidebar -->
                @include('priv.inc.sidebar')
                <!-- end: sidebar -->

                <section role="main" class="content-body">
                    @yield('privContent')
                    <!-- end: page -->
                </section>
            </div>

   
        </section>

        <!-- Vendor -->
        <script src="{{asset('assets/vendor/jquery/jquery.js')}}"></script>
        <script src="{{asset('assets/vendor/popper/umd/popper.min.js')}}"></script>
        <script src="{{asset('assets/vendor/bootstrap/js/bootstrap.js')}}"></script>
        <script src="{{asset('assets/js/theme.js')}}"></script>

        <script src="{{asset('assets/js/custom.js')}}"></script>
        
        <script src="{{ asset('assets/vendor/toastr/toastr.min.js')}}"></script>
        @yield('scripts')
        <script type="text/javascript">
            
            @if(Session::has('message'))
              var type = "{{ Session::get('alert-type', 'info') }}";
              switch(type){
                  case 'info':
                      toastr.info("{{ Session::get('message') }}");
                      break;

                  case 'warning':
                      toastr.warning("{{ Session::get('message') }}");
                      break;

                  case 'success':
                      toastr.success("{{ Session::get('message') }}");
                      break;

                  case 'error':
                      toastr.error("{{ Session::get('message') }}");
                      break;
              }
            @endif

            @if(count($errors) > 0)
                @foreach($errors->all() as $error)
                    toastr.error("{{ $error }}");
                @endforeach
            @endif
        </script>
    </body>
</html>