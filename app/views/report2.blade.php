@extends('layout.BaseTemplate')

@section('title')
	Report
@stop

@section('content')
<?php
	$i = 0;
	$monthList = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
	foreach($months as $month)
	{
		$data[$i][0] = $monthList[($month->Month - 1)];
		$j = 1;
		foreach($wattageUsages as $wattageUsage)
		{
			if($wattageUsage->Month == $month->Month)
			{
				$data[$i][$j] = $wattageUsage->TotalUsage;
				$j++;
			}
		}
		
		$i++;
	}
	$labels[0] = "Months";
	$i = 1;
	foreach($wattageUsages as $wattageUsage)
	{
		if($wattageUsage->Month == "11")
		{
			$labels[$i] = $wattageUsage->CategoryID;
			$i++;
		}
	}
	$dataShow = null;
	for($i=0;$i<(count($data)+1);$i++)
	{
		if ($i==0)
		{
			$dataShow[$i] = $labels;
		}
		else
		{
			$dataShow[$i] = $data[$i-1];
		}
		echo print_r($dataShow[$i])."<br/>";
	}

?>
<div class="panel panel-default">
	<div class="panel-heading headerFont">
		View Report
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-16">
				<div id="chart_div" style="width: 100%; height: 500;"></div>
			</div>
		</div>
	</div>
</div>
@stop


@section('javascript')
<!-- Google Chart SCRIPTS -->
{{ HTML::script('assets/js/google-charts/jsapi.js'); }}
{{ HTML::script('assets/js/google-charts/uds_api_contents.js'); }}
<script type="text/javascript">

google.setOnLoadCallback(drawChart);
function drawChart() {

	var dataShow = <?php echo json_encode($dataShow) ?>;
	console.log(dataShow);
	var data = google.visualization.arrayToDataTable(dataShow);

	var options = {
		title: 'Company Performance',
		hAxis: 	{	'title': 'Year', 
					'titleTextStyle': {color: 'black'}
				}
	};

	var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));

	chart.draw(data, options);

}
</script>
@stop

<?php
	//print_r($wattageUsages);
	// foreach($wattageUsages as $wattageUsage)
	// {
	// 	echo $wattageUsage->CategoryID." ".$wattageUsage->Month." ".$wattageUsage->Year." ".$wattageUsage->TotalUsage."<br/>"  ;
	// }
?>
		