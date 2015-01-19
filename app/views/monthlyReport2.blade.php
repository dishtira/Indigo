<div class="panel-heading headerFont">
	View Report
</div>
<div class="panel-body">
	@if(count($availableDates) <= 0)
	<div class="form-group">
		<label>There is no available report yet</label>
	</div>
	@else
	<div class="row">
		<div class="col-md-16">
			<div class="row">
				<div class="col-md-10" style="margin-left:5px;">
				<form class="form-inline" role="form" action="{{ URL::route('report') }}" method="post">
					<label>Report of</label>
					<select class="form-control" name="month" id="month">
						<?php
							foreach($availableDates as $availableDate)
							{
								if($availableDate->Year == $yearShow)
								{
						?>
						<option value="{{$availableDate->Month}}" {{ ($availableDate->Month == $monthShow) ? 'selected=""' : '' }} > {{ $monthList[($availableDate->Month - 1)] }} </option>
						<?php
								}
							}
						?>
					</select>
					<select class="form-control" name="year" onchange="changeMonthList(this)">
						<?php
							$i = 0;
							foreach($years as $year)
							{
						?>
						<option value="{{$year->Year}}" {{ ($year->Year == $yearShow) ? 'selected=""' : '' }} > {{ $year->Year }} </option>
						<?php
								$monthReplacement[$i] = "";
								foreach($availableDates as $availableDate)
								{
									if($availableDate->Year == $year->Year)
									{
										$monthReplacement[$i] .=  "<option value=\"".$availableDate->Month."\">".$monthList[($availableDate->Month - 1)]."</option>";
									}
								}
								$i++;
							}
						?>
					</select>
					<input type="submit" value="View Report" class="btn btn-success">
				</form>
				</div>
			</div>
			@if(count($wattageUsages) <= 0)
				There is no report for this month
			@else
			<div id="chart_div" style="width: 100%; height: 500;"></div>
			<hr/>
			<div class="table-responsive" style="margin-left:5px; margin-right:5px;">
			    <table class="table table-striped table-bordered table-hover" id="dataTablesReport">
			        <thead>
			            <tr>  
			                <th>Category ID</th>
			                <th>Category Name</th>
			                <th>Total Usage</th>
			            </tr>
			        </thead>
			        <tbody>
			        <?php
			            foreach($wattageUsages as $wattageUsage)
			            {
			        ?>
			            <tr class="odd gradeX">
			                <td>{{$wattageUsage->CategoryID}}</td>
			                <td>{{$wattageUsage->CategoryName}}</td>
			                <td>{{$wattageUsage->TotalUsage}}</td>
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
	@endif
</div>

@section('javascript')
@if(count($availableDates) > 0)

<!-- Google Chart SCRIPTS -->
{{ HTML::script('assets/js/google-charts/jsapi.js'); }}
{{ HTML::script('assets/js/google-charts/uds_api_contents.js'); }}
<script type="text/javascript">

$(window).resize(function(){
  drawChart();
});

$(document).ready(function () {
    $('#dataTablesReport').dataTable();
});

function changeMonthList(dropdown)
{
	monthReplacement = <?php echo json_encode($monthReplacement) ?>;
	var value = dropdown.selectedIndex;
	document.getElementById('month').innerHTML = monthReplacement[dropdown.selectedIndex];
}

google.setOnLoadCallback(drawChart);
function drawChart() {
	var dataShow = <?php echo json_encode($data) ?>;
	var title = <?php echo json_encode($title) ?>;
	if (title != null && title != "")
	{
		var data = google.visualization.arrayToDataTable(dataShow);

		var options = {
			title: 'Total Watt Usage in '+title,
			hAxis: 	{	'title': 'Categories', 
						'titleTextStyle': {color: 'black'}
					}
		};

		var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));

		chart.draw(data, options);
	}
	
}
</script>

@endif
@stop