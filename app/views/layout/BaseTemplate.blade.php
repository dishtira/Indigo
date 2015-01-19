<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Indigo Home Automation System</title>
	<!-- BOOTSTRAP STYLES-->
    {{ HTML::style('assets/css/bootstrap.css'); }}
    <!-- BOOTSTRAP DIALOG STYLES-->
    {{ HTML::style('assets/css/bootstrap-dialog.min.css'); }}
    <!-- FONTAWESOME STYLES-->
    {{ HTML::style('assets/css/font-awesome.css'); }}
    <!-- CUSTOM STYLES-->
    {{ HTML::style('assets/css/custom.css'); }}
    <!-- GOOGLE FONTS-->
    {{ HTML::style('http://fonts.googleapis.com/css?family=Open+Sans'); }}
    <!-- TABLE STYLES-->
    {{ HTML::style('assets/js/dataTables/dataTables.bootstrap.css'); }}
    <!-- CUSTOM STYLES-->
    {{ HTML::style('assets/css/indigoCss.css'); }}
</head>
<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-cls-top " role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{URL::route('home')}}">Indigo</a> 
            </div>
            <div style="color: white; padding: 15px 50px 5px 50px; float: right;font-size: 16px;">
            @if(Auth::check())
                <span style="margin-right:10px;">Welcome, {{ Auth::user()->Username }}</span>
                <span><a href="{{ URL::route('logout') }}"><button class="btn btn-danger">Logout <i class="glyphicon glyphicon-log-out"></i></button></a></span>
            @else
                <form class="form-inline" role="form" action="{{ URL::route('doLogin') }}" method="post">
                    <div class="form-group @if ($errors->has('username')) has-error @endif">
                        <font color="red">
                        <label>
                        @if($errors->first('location') == 'login' || Session::has('errMessage'))
                            @if($errors->has('username') && $errors->has('password'))
                                Username and password must be filled
                            @elseif($errors->has('username'))
                                Username must be filled
                            @elseif ($errors->has('password'))
                                Password must be filled
                            @elseif (Session::has('errMessage'))
                                {{ Session::get('errMessage') }}
                            @endif
                        @endif
                        </label>
                        </font>
                        <input type="text" class="form-control" name="username" placeholder="Username" value="{{ (Input::old('username')) ? e(Input::old('username')) : '' }}">
                    </div>
                    <div class="form-group @if ($errors->has('password')) has-error @endif">
                        <input type="password" class="form-control" name="password" placeholder="Password">
                    </div>
                    {{ Form::token() }}
                    <button type="submit" class="btn btn-primary">Login <i class="glyphicon glyphicon-log-in"></i></button>
                </form>
            @endif
            </div>
        </nav>   
           <!-- /. NAV TOP  -->
        @include('layout.Navigation')  
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                      <h2>@yield('title')</h2>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="col-md-12">
                        @yield('content')
                    </div>
                </div>              
        </div>
             <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->
        </div>
     <!-- /. WRAPPER  -->

     <!-- Bootstrap modal dialog -->
     @include('layout.Modal')

    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->
    {{ HTML::script('assets/js/jquery-1.10.2.js'); }}
    <!-- BOOTSTRAP SCRIPTS -->
    {{ HTML::script('assets/js/bootstrap.min.js'); }}
    <!-- BOOTSTRAP SCRIPTS -->
    {{ HTML::script('assets/js/bootstrap-dialog.min.js'); }}
    <!-- METISMENU SCRIPTS -->
    {{ HTML::script('assets/js/jquery.metisMenu.js'); }}

    {{ HTML::script('assets/js/dataTables/jquery.dataTables.js'); }}

    {{ HTML::script('assets/js/dataTables/dataTables.bootstrap.js'); }}
    @include('javascript.indigoJS')
    @yield('javascript')
     <!-- CUSTOM SCRIPTS -->
    {{ HTML::script('assets/js/custom.js'); }}

    

</body>
</html>
