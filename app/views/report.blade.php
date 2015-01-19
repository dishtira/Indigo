@extends('layout.BaseTemplate')

@section('title')
	Report
@stop

@section('content')
<?php
    if (count($availableDays) > 0)
    {
        $monthList = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        
        $chartDataDay[0][0] = "Category Name";
        $chartDataDay[0][1] = "Value";

        $i = 1;
        foreach($dayUsages as $dayUsage)
        {
            $chartDataDay[$i][0] = $dayUsage->CategoryName;
            $chartDataDay[$i][1] = (double)$dayUsage->TotalUsage;
            // $monthShow = $monthUsage->Month;
            // $yearShow = $monthUsage->Year;
            $chartDayTitle =  $dayUsage->Day." ".$monthList[($dayUsage->Month -1)]." ".$dayUsage->Year;
            $i++;
        }

        
        $chartDataMonth[0][0] = "Category Name";
        $chartDataMonth[0][1] = "Value";

        $i = 1;
        foreach($monthUsages as $monthUsage)
        {
            $chartDataMonth[$i][0] = $monthUsage->CategoryName;
            $chartDataMonth[$i][1] = (double)$monthUsage->TotalUsage;
            // $monthShow = $monthUsage->Month;
            // $yearShow = $monthUsage->Year;
            $chartMonthTitle =  $monthList[($monthUsage->Month -1)]." ".$monthUsage->Year;
            $i++;
        }

        $chartDataYear[0][0] = "Category Name";
        $chartDataYear[0][1] = "Value";   

        $i = 1;
        foreach($yearUsages as $yearUsage)
        {
            $chartDataYear[$i][0] = $yearUsage->CategoryName;
            $chartDataYear[$i][1] = (double)$yearUsage->TotalUsage;
            // $monthShow = $monthUsage->Month;
            // $yearShow = $monthUsage->Year;
            $chartYearTitle =  $yearUsage->Year;
            $i++;
        }
    }

?>

<div class="panel panel-default">
    @if(count($availableDays) <= 0 )
        <div class="form-group">
            <label>There is no available report yet</label>
        </div>
    @else
	<div style="margin-left:5px; margin-right:5px;">
	<ul class="nav nav-tabs">
        <li class="active" onclick="redrawChart()"><a href="#dailyReport" data-toggle="tab">Daily</a>
        </li>
        <li class="" onclick="redrawChart()"><a href="#monthlyReport" data-toggle="tab">Monthly</a>
        </li>
        <li class="" onclick="redrawChart()"><a href="#yearlyReport" data-toggle="tab">Yearly</a>
        </li>
    </ul>
	<div class="tab-content">
        <div class="tab-pane fade active in" id="dailyReport">
            <div class="row">
                <div class="col-md-12" style="margin-left:5px;">
                    <h4>Daily Report</h4>
                    <div class="row">
                        <div class="col-md-8">
                            <form class="form-inline" role="form" method="post" action="">
                                <label>Report of</label>
                                <select name="day" class="form-control" id="day">
                                <?php
                                    foreach($availableDays as $availableDay)
                                    {
                                        if($availableDay->Month == $monthShow)
                                        {
                                ?>
                                    <option value="{{ $availableDay->Day }}" @if($availableDay->Day == $dayShow) selected="" @endif >
                                        {{ $availableDay->Day }}
                                    </option>
                                <?php
                                        }
                                    }
                                ?>
                                </select>

                                <select name="month" class="form-control" id="monthDay" onchange="changeDataDay('month', this)">
                                <?php
                                    foreach($availableMonths as $availableMonth)
                                    {
                                        if($availableMonth->Year == $yearShow)
                                        {
                                ?>
                                    <option value="{{ $availableMonth->Month }}" @if($availableMonth->Month == $monthShow) selected="" @endif >
                                        {{ $monthList[$availableMonth->Month -1] }}
                                    </option>
                                <?php
                                        }
                                    }
                                ?>
                                </select>

                                <select name="year" class="form-control" id="yearDay" onchange="changeDataDay('year', this)">
                                <?php
                                    $yearCounter = 0;
                                    foreach($years as $year)
                                    {
                                        $monthCounter = 0;
                                        $dataMonth[$yearCounter] = "";
                                        foreach($availableMonths as $availableMonth)
                                        {
                                            if ($availableMonth->Year == $year->Year)
                                            {
                                                $dataMonth[$yearCounter] .=  "<option value=\"".$availableMonth->Month."\">".$monthList[$availableMonth->Month -1]."</option>";

                                                $dataDay[$yearCounter][$monthCounter] = "";
                                                foreach($availableDays as $availableDay)
                                                {
                                                    if($availableDay->Month == $availableMonth->Month && $availableDay->Year == $year->Year)
                                                    {
                                                        $dataDay[$yearCounter][$monthCounter] .= "<option value=\"".$availableDay->Day."\">".$availableDay->Day."</option>";
                                                    }
                                                }
                                                $monthCounter++;
                                            }
                                        }
                                        $yearCounter++;
                                ?>
                                    <option value="{{ $year->Year }}" @if($year->Year == $yearShow) selected="" @endif>{{$year->Year}}</option>
                                <?php
                                    }
                                ?>
                                </select>
                                <input type="submit" value="View Report" class="btn btn-success">
                            </form>
                        </div>
                    </div>
                    @if(count($dayUsages) <= 0 )
                    <div class="form-group">
                        <label>There is no available report yet</label>
                    </div>
                    @else
                    <div id="chartDay_div" style="width: 100%; height: 500;"></div>
                    <hr/>
                    <div class="table-responsive" style="margin-left:5px; margin-right:5px;">
                        <table class="table table-striped table-bordered table-hover" id="dayDataTableReport">
                            <thead>
                                <tr>  
                                    <th>Category ID</th>
                                    <th>Category Name</th>
                                    <th>Total Usage</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                foreach($dayUsages as $dayUsage)
                                {
                            ?>
                                <tr class="odd gradeX">
                                    <td>{{$dayUsage->CategoryID}}</td>
                                    <td>{{$dayUsage->CategoryName}}</td>
                                    <td>{{$dayUsage->TotalUsage}}</td>
                                </tr>
                            <?php

                                }
                            ?>                                           
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
                <!-- end of day -->
        </div>
        <div class="tab-pane fade" id="monthlyReport">
            <div class="row">
                <div class="col-md-12" style="margin-left:5px;">
                    <h4>Monthly Report</h4>
                    <div class="row">
                        <div class="col-md-8">
                            <form class="form-inline" role="form" method="post" action="">
                                <label>Report of</label>
                                <select name="month" class="form-control" id="month" onchange="changeDataDay('month', this)">
                                <?php
                                    foreach($availableMonths as $availableMonth)
                                    {
                                        if($availableMonth->Year == $yearShow)
                                        {
                                ?>
                                    <option value="{{ $availableMonth->Month }}" @if($availableMonth->Month == $monthShow) selected="" @endif >
                                        {{ $monthList[$availableMonth->Month -1] }}
                                    </option>
                                <?php
                                        }
                                    }
                                ?>                            
                                </select>

                                <select name="year" class="form-control" id="yearMonth" onchange="changeDataDay('year', this)">
                                    <?php
                                        foreach($years as $year)
                                        {
                                    ?>
                                        <option value="{{ $year->Year }}" @if($year->Year == $yearShow) selected="" @endif>{{$year->Year}}</option>
                                    <?php
                                        }
                                    ?>
                                </select>

                                <input type="submit" value="View Report" class="btn btn-success">
                            </form>
                        </div>
                    </div>
                    @if(count($monthUsages) <= 0 )
                    <div class="form-group">
                        <label>There is no available report yet</label>
                    </div>
                    @else
                    <div id="chartMonth_div" style="width: 100%; height: 500;"></div>
                    <hr/>
                    <div class="table-responsive" style="margin-left:5px; margin-right:5px;">
                        <table class="table table-striped table-bordered table-hover" id="monthDataTableReport">
                            <thead>
                                <tr>  
                                    <th>Category ID</th>
                                    <th>Category Name</th>
                                    <th>Total Usage</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                foreach($monthUsages as $monthUsage)
                                {
                            ?>
                                <tr class="odd gradeX">
                                    <td>{{$monthUsage->CategoryID}}</td>
                                    <td>{{$monthUsage->CategoryName}}</td>
                                    <td>{{$monthUsage->TotalUsage}}</td>
                                </tr>
                            <?php

                                }
                            ?>                                           
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
            <!-- end of month -->
        </div>
        <div class="tab-pane fade" id="yearlyReport">
            <div class="row">
                <div class="col-md-12" style="margin-left:5px;">
                    <h4>Yearly Report</h4>
                    <div class="row">
                        <div class="col-md-8">
                            <form class="form-inline" role="form" method="post" action="">
                                <label>Report of</label>
                                <select name="year" class="form-control" id="year" onchange="changeDataDay('year', this)">
                                    <?php
                                        foreach($years as $year)
                                        {
                                    ?>
                                        <option value="{{ $year->Year }}" @if($year->Year == $yearShow) selected="" @endif>{{$year->Year}}</option>
                                    <?php
                                        }
                                    ?>
                                </select>

                                <input type="submit" value="View Report" class="btn btn-success">
                            </form>
                        </div>
                    </div>
                    @if(count($yearUsages) <= 0 )
                    <div class="form-group">
                        <label>There is no available report yet</label>
                    </div>
                    @else
                    <div id="chartYear_div" style="width: 100%; height: 500;"></div>
                    <hr/>
                    <div class="table-responsive" style="margin-left:5px; margin-right:5px;">
                        <table class="table table-striped table-bordered table-hover" id="yearDataTableReport">
                            <thead>
                                <tr>  
                                    <th>Category ID</th>
                                    <th>Category Name</th>
                                    <th>Total Usage</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                foreach($yearUsages as $yearUsage)
                                {
                            ?>
                                <tr class="odd gradeX">
                                    <td>{{$yearUsage->CategoryID}}</td>
                                    <td>{{$yearUsage->CategoryName}}</td>
                                    <td>{{$yearUsage->TotalUsage}}</td>
                                </tr>
                            <?php

                                }
                            ?>                                           
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
            <!-- end of year -->
        </div>
    </div>
    </div>
@endif
</div>
@stop

@section('javascript')
@if(count($availableDays) > 0 && count($dayUsages) > 0)
<!-- Google Chart SCRIPTS -->
{{ HTML::script('assets/js/google-charts/jsapi.js'); }}
{{ HTML::script('assets/js/google-charts/uds_api_contents.js'); }}
<script type="text/javascript">

function redrawChart()
{
    drawChartDay();
    drawChartMonth();
    drawChartYear();
}

$(document).ready(function () {
    $('#dayDataTableReport').dataTable();
    $('#monthDataTableReport').dataTable();
    $('#yearDataTableReport').dataTable();
});

function changeDataDay(by,dropdown)
{
    dataMonth = <?= json_encode($dataMonth) ?>;
    dataDay = <?= json_encode($dataDay) ?>;

    
    if (by == "year")
    {
        indexYear = dropdown.selectedIndex;
        document.getElementById('monthDay').innerHTML = dataMonth[indexYear];
        document.getElementById('month').innerHTML = dataMonth[indexYear];
        document.getElementById('yearMonth').selectedIndex = indexYear;
        document.getElementById('yearDay').selectedIndex = indexYear;
        document.getElementById('year').selectedIndex = indexYear;
    }
    else
    {
        indexYear = document.getElementById('year').selectedIndex;
    }
    if (by == "month")
    {
        indexMonth = dropdown.selectedIndex;
        document.getElementById('month').selectedIndex = indexMonth;
        document.getElementById('monthDay').selectedIndex = indexMonth;
    }
    else
    {
        indexMonth = document.getElementById('month').selectedIndex;
    }
    document.getElementById('day').innerHTML = dataDay[indexYear][indexMonth];
    console.log("month "+indexMonth+" year "+indexYear);
}

$(window).resize(function(){
    redrawChart();
});

google.setOnLoadCallback(drawChartDay);
function drawChartDay() {
    var chartDataDay = <?php echo json_encode($chartDataDay) ?>;
    var chartDayTitle = <?php echo json_encode($chartDayTitle) ?>;
    if (chartDayTitle != null && chartDayTitle != "")
    {
        var dataDayShow = google.visualization.arrayToDataTable(chartDataDay);

        var dayChartOptions = {
            title: 'Total Watt Used in '+chartDayTitle,
            hAxis:  {   'title': 'Categories', 
                        'titleTextStyle': {color: 'black'}
                    }
        };

        var chartDay = new google.visualization.ColumnChart(document.getElementById('chartDay_div'));

        chartDay.draw(dataDayShow, dayChartOptions);
    }
    
}

google.setOnLoadCallback(drawChartMonth);
function drawChartMonth() {
    var chartDataMonth = <?php echo json_encode($chartDataMonth) ?>;
    var chartMonthTitle = <?php echo json_encode($chartMonthTitle) ?>;
    if (chartMonthTitle != null && chartMonthTitle != "")
    {
        var dataMonthShow = google.visualization.arrayToDataTable(chartDataMonth);

        var monthChartOptions = {
            title: 'Total Watt Used in '+chartMonthTitle,
            hAxis:  {   'title': 'Categories', 
                        'titleTextStyle': {color: 'black'}
                    }
        };

        var chartMonth = new google.visualization.ColumnChart(document.getElementById('chartMonth_div'));

        chartMonth.draw(dataMonthShow, monthChartOptions);
    }
    
}

google.setOnLoadCallback(drawChartYear);
function drawChartYear() {
    var chartDataYear = <?php echo json_encode($chartDataYear) ?>;
    var chartYearTitle = <?php echo json_encode($chartYearTitle) ?>;
    if (chartYearTitle != null && chartYearTitle != "")
    {
        var dataYearShow = google.visualization.arrayToDataTable(chartDataYear);

        var yearChartOptions = {
            title: 'Total Watt Used in '+chartYearTitle,
            hAxis:  {   'title': 'Categories', 
                        'titleTextStyle': {color: 'black'}
                    }
        };

        var chartYear = new google.visualization.ColumnChart(document.getElementById('chartYear_div'));

        chartYear.draw(dataYearShow, yearChartOptions);
    }
    
}

</script>
@endif
@stop
