<?php
	$i = 1;
	$dayData[0][0] = "CategoryID";
	$dayData[0][1] = "Total Usage";
	$dayTitle = "";
	$monthList = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
	foreach($dayUsages as $dayUsage)
	{
		$dayData[$i][0] = $dayUsage->CategoryName;
		$dayData[$i][1] = (double)$dayUsage->TotalUsage;
		// $monthShow = $dayUsage->Month;
		// $yearShow = $dayUsage->Year;
		$dayTitle = $dayUsage->Day." ".$monthList[($dayUsage->Month -1)]." ".$dayUsage->Year;
		$i++;
	}
?>
<div>
	@if(count($availableDays) <= 0)
	<div class="form-group">
		<label>There is no available report yet</label>
	</div>
	@else
	<div class="row">
		<div class="col-md-10">
			<div class="row">
				<div class="col-md-8" style="margin-left:5px;">
				<form class="form-inline" role="form" action="{{ URL::route('report') }}" method="post">
					<label>Report of</label>
					<select class="form-control" name="day" id="day">
						<?php
							foreach($availableDays as $availableDay)
							{
								if($availableDay->Month == $monthShow && $availableDay->Year == $yearShow)
								{
						?>
						<option value="{{$availableDay->Day}}" {{ ($availableDay->Day == $dayShow) ? 'selected=""' : '' }} > {{ $availableDay->Day }} </option>
						<?php
								}
							}
						?>
					</select>
					<select class="form-control" name="month" id="month">
						<?php
							foreach($availableDays as $availableDay)
							{
								if($availableDay->Year == $yearShow)
								{
						?>
						<option value="{{$availableDay->Month}}" {{ ($availableDay->Month == $monthShow) ? 'selected=""' : '' }} > {{ $monthList[($availableDay->Month - 1)] }} </option>
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
								$j=0;
								foreach($availableDays as $availableDay)
								{
									if($availableDay->Year == $year->Year)
									{
										$monthReplacement[$i] .=  "<option value=\"".$availableDay->Month."\">".$monthList[($availableDay->Month - 1)]."</option>";
										$dayReplacement[$i][$j] = "";
										foreach($availableDays as $a)
										{
											if($availableDay->Month == $a->Month && $year->Year == $a->Year)
											{
												$dayReplacement[$i][$j] .= "<option value=\"".$availableDay->Day."\">".$availableDay->Day."</option>";
											}
										}
										$j++;
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
			@if(count($dayUsages) <= 0)
				There is no report for this date
			@else
			<div id="dayChart_div" style="width: 100%; height: 500;"></div>
			<hr/>
			<div class="table-responsive" style="margin-left:5px; margin-right:5px;">
			    <table class="table table-striped table-bordered table-hover" id="dayDataTablesReport">
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
	@endif
</div>

@section('javascript')

<!-- Google Chart SCRIPTS -->
@if(count($availableDays) > 0)

<script type="text/javascript">

$(window).resize(function(){
  drawChartDay();
});

$(document).ready(function () {
    $('#dayDataTablesReport').DataTable();
});

function changeDayList(dropdown)
{
	dayReplacement = <?php echo json_encode($dayReplacement) ?>;
	console.log(dayReplacement);
}

google.setOnLoadCallback(drawChartDay);
function drawChartDay() {
	var dayDataShow = <?php echo json_encode($dayData) ?>;
	var dayTitle = <?php echo json_encode($dayTitle) ?>;
	if (dayTitle != null && dayTitle != "")
	{
		var dayData = google.visualization.arrayTodayDataTable(dayDataShow);

		var dayOptions = {
			dayTitle: 'Total Watt Usage in '+dayTitle,
			hAxis: 	{	'dayTitle': 'Categories', 
						'dayTitleTextStyle': {color: 'black'}
					}
		};

		var dayChart = new google.visualization.ColumnChart(document.getElementById('dayChart_div'));

		dayChart.draw(dayData, dayOptions);
	}
	
}
</script>

@endif
@stop