<!doctype html>
<html class="fixed">
    <head>

        <!-- Basic -->
        <meta charset="UTF-8">

        <meta name="keywords" content="HTML5 Admin Template" />
        <meta name="description" content="Porto Admin - Responsive HTML5 Template">
        <meta name="author" content="okler.net">

        <!-- Mobile Metas -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

        <!-- Web Fonts  -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

        <!-- Vendor CSS -->
        <link rel="stylesheet" href="{{asset('assets/vendor/bootstrap/css/bootstrap.css')}}" />

        <!-- Theme CSS -->
        <link rel="stylesheet" href="{{asset('assets/css/theme.css')}}" />

        <!-- Skin CSS -->
        <link rel="stylesheet" href="{{asset('assets/css/skins/default.css')}}" />

        <!-- Theme Custom CSS -->
        <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">


    </head>
    <body>
        <!-- start: page -->
        <section class="body-sign">
            <div class="center-sign">

                <div class="panel card-sign">
                    <div class="card-title-sign mt-3 text-right">
                        <h2 class="title text-uppercase font-weight-bold m-0"><i class="fas fa-user mr-1"></i> Sign In</h2>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('login') }}" method="post">
                            @csrf
                            <div class="form-group mb-3">
                                <label>email</label>
                                <div class="input-group">
                                    <input name="email" type="email" class="form-control form-control-lg" />
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <div class="clearfix">
                                    <label class="float-left">Password</label>
                                </div>
                                <div class="input-group">
                                    <input name="password" type="password" class="form-control form-control-lg" />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-8">
                                </div>
                                <div class="col-sm-4 text-right">
                                    <button type="submit" class="btn btn-primary mt-2">Sign In</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </section>
    </body>
</html>