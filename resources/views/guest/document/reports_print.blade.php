<?php

   $years = ['2020'];
   $months = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];

?>
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
            <div class="col-xs-2"></div>
            <div class="col-xs-2">
              <img src="{{ asset('favicon.png') }}" style="width:200px;">
            </div>
            <div class="col-xs-7" style="text-align: center; padding-right:125px;">
              <span>Republic of the Philippines</span><br>
              <span style="font-size:15px; font-weight:bold;">SUGAR REGULATORY ADMINISTRATION</span><br>
              <span>North Avenue, Diliman, Quezon City</span><br>
              <span>Document Management System</span><br>
              <span>Number of Imports by Month</span><br>
            </div>
            <div class="col-xs-1"></div>
        </div>
        <div class="col-xs-1"></div>
      </div>
    </div>

    <div class="row" style="padding-top:20px;">
      <div class="col-xs-12 table-responsive">
        <table class="table table-bordered">

              <thead>

                <tr>
                  <th>Year</th>
                  <th>Jan</th>
                  <th>Feb</th>
                  <th>Mar</th>
                  <th>Apr</th>
                  <th>May</th>
                  <th>Jun</th>
                  <th>Jul</th>
                  <th>Aug</th>
                  <th>Sep</th>
                  <th>Oct</th>
                  <th>Nov</th>
                  <th>Dec</th>
                  <th>Total</th>
                </tr>
              </thead>

              <tbody>

                @foreach ($years as $data_year)

                  <tr>

                    <?php $count_total = 0; ?>

                    <td>{{ $data_year }}</td>

                    @foreach ($months as $data_month)
                    
                      <?php $count = 0; ?>

                      @foreach ($documents as $data_doc)

                        <?php
                          $year_created = __dataType::date_parse($data_doc->created_at, 'Y');
                          $month_created = __dataType::date_parse($data_doc->created_at, 'm');
                        ?>

                        @if ($year_created == $data_year && $month_created == $data_month)
                          
                          <?php $count++; ?>

                        @endif
                        
                      @endforeach

                      <td>{{ $count }}</td>

                      <?php $count_total += $count ?>

                    @endforeach

                    <td><b>{{ $count_total }}</b></td>  

                  </tr> 

                @endforeach

              </tbody>

          </table>
      </div>

    </div>

  </section>

</body>
</html>
