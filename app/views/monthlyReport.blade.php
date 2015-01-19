<?php
	$i = 1;
	$data[0][0] = "CategoryID";
	$data[0][1] = "Total Usage";
	$title = "";
	$monthList = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
	foreach($monthUsages as $monthUsage)
	{
		$data[$i][0] = $monthUsage->CategoryName;
		$data[$i][1] = (double)$monthUsage->TotalUsage;
		// $monthShow = $monthUsage->Month;
		// $yearShow = $monthUsage->Year;
		$title = $monthList[($monthUsage->Month -1)]." ".$monthUsage->Year;
		$i++;
	}
?>
<div>
	@if(count($availableMonths) <= 0)
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
					<select class="form-control" name="month" id="month">
						<?php
							foreach($availableMonths as $availableMonth)
							{
								if($availableMonth->Year == $yearShow)
								{
						?>
						<option value="{{$availableMonth->Month}}" {{ ($availableMonth->Month == $monthShow) ? 'selected=""' : '' }} > {{ $monthList[($availableMonth->Month - 1)] }} </option>
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
								foreach($availableMonths as $availableMonth)
								{
									if($availableMonth->Year == $year->Year)
									{
										$monthReplacement[$i] .=  "<option value=\"".$availableMonth->Month."\">".$monthList[($availableMonth->Month - 1)]."</option>";
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
			@if(count($monthUsages) <= 0)
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
	@endif
</div>

<?php
if(count($availableMonths) > 0)
{
?>
<script type="text/javascript">


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
<?php
}
?>