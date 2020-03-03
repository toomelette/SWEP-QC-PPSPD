<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SRA Document Management System Report</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  @include('layouts.css-plugins')
</head>

<body onload="window.print();" onafterprint="window.close()" style="overflow:hidden;">

  <section class="invoice">

    <div class="row invoice-info">
      <div class="row" style="padding:10px;">
        <div class="col-xs-1"></div>
        <div class="col-xs-12">
            <div class="col-xs-1"></div>
            <div class="col-xs-3">
              <img src="{{ asset('favicon.png') }}" style="width:200px;">
            </div>
            <div class="col-xs-7" style="text-align: center; padding-right:125px;">
              <span>Republic of the Philippines</span><br>
              <span style="font-size:15px; font-weight:bold;">SUGAR REGULATORY ADMINISTRATION</span><br>
              <span>North Avenue, Diliman, Quezon City</span><br>
              <span>Document Management System</span><br>
              <span>Number of Imports by Month</span><br>
              <span>as of {{ __dataType::date_scope(Request::get('df'), Request::get('dt')) }}</span>
            </div>
            <div class="col-xs-1"></div>
        </div>
        <div class="col-xs-1"></div>
      </div>
    </div>



    <div class="row" style="padding-top:20px;">
      <div class="col-xs-12 table-responsive">
        <table class="table table-striped">
          <thead>
          <tr>
            <th>Month</th>
            <th>Imports</th>
          </tr>
          </thead>
          <tbody>

            @foreach(__dynamic::months_between_dates(Request::get('df'), Request::get('dt')) as $key => $data)
              <tr>
                <td>{{ $data }}</td>
                <td>

                  <?php $count = 0; ?>

                  @foreach($documents as $data)
                    @if(__dataType::date_parse($data->created_at, 'm') == $key)
                      <?php $count+=1; ?>
                    @endif
                  @endforeach

                  {{ $count }}

                </td>
              </tr>
            @endforeach 

            <tr>
                <td><b>TOTAL</b></td>
                <td><b>{{ $documents->count() }}</b></td>
              </tr>
          </tbody>
        </table>
      </div>

    </div>

  </section>

</body>
</html>
