<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SRA | Sugar Regulatory Administration</title>
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @include('layouts.css-plugins')

    @yield('extras')

  </head>
  <body class="hold-transition skin-green layout-top-nav">
    <div class="wrapper">
      <header class="main-header">
        <nav class="navbar navbar-static-top">
          <div class="container-fluid">
            <div class="navbar-header">
              <div class="col-md-12">
                <div class="col-md-2">
                  <img src="{{ asset('favicon.png') }}" style="height:47px; width:54px; padding:5px;"> 
                </div>
                <div class="col-md-10">
                  <a href="{{ route('guest.document.index') }}" class="navbar-brand"><b>Document Management System</b></a> 
                </div>
              </div>
            </div>
            <div class="navbar-custom-menu">
              <ul class="nav navbar-nav">
                <li class="notifications-menu {{ Route::currentRouteNamed('guest.document.create') ? 'active' : '' }}">
                  <a href="{{ route('guest.document.create') }}">Import</a>
                </li>
                <li class="notifications-menu {{ Route::currentRouteNamed('guest.folder.index') ? 'active' : '' }}">
                  <a href="{{ route('guest.folder.index') }}">Folders</a>
                </li>
                <li class="notifications-menu {{ Route::currentRouteNamed('guest.document.index') ? 'active' : '' }}">
                  <a href="{{ route('guest.document.index') }}">Documents</a>
                </li>
                <li class="notifications-menu {{ Route::currentRouteNamed('guest.document.archives') ? 'active' : '' }}">
                  <a href="{{ route('guest.document.archives') }}">Archives</a>
                </li>
                <li class="notifications-menu {{ Route::currentRouteNamed('guest.document.reports') ? 'active' : '' }}">
                  <a href="{{ route('guest.document.reports') }}">Reports</a>
                </li>
                <li class="notifications-menu {{ Route::currentRouteNamed('auth.showLogin') ? 'active' : '' }}">
                  <a href="{{ route('auth.showLogin') }}">Admin</a>
                </li>
            </div>
          </div>
        </nav>
      </header>

      <div class="content-wrapper">
        <div class="container-fluid">
          @yield('content')
        </div>
      </div>

      {{-- FORM ERROR MODAL --}}  
      <div class="modal fade modal-danger" data-backdrop="static" id="error_fields">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button class="close" data-dismiss="modal">
                <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title">
                <i class="fa fa-exclamation-triangle"></i> 
                &nbsp;Whoops!
              </h4>
            </div>
            <div class="modal-body">
              <p style="font-size: 17px;">
                Please check for errors in your input fields.
              </p>
            </div>
            <div class="modal-footer">
              <button class="btn btn-outline" data-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
      
      <footer class="main-footer">
        <div class="container-fluid">
          <div class="pull-right hidden-xs">
            <b>Version</b> 1.1
          </div>
          <strong>Copyright &copy; 2019-2020 .</strong> All rights
          reserved.
        </div>
      </footer>
      
    </div>

    @include('layouts.js-plugins')

    @yield('modals')
      
    @yield('scripts')

    @if($errors->any())
      <script type="text/javascript">
        $("#error_fields").modal("show");
      </script>
    @endif

  </body>
</html>