<?php

$applianceRes = DB::table('appliance')
			->join('category', 'appliance.CategoryID', '=', 'category.CategoryID')
			->select('appliance.CategoryID','category.CategoryName', 'appliance.PinLocation')
			->orderby('appliance.PinLocation','asc')
			->get();
for( $i=0;$i<count($applianceRes);$i++ )
{
	$categoryIDs[$i] = $applianceRes[$i]->CategoryID;
	$categoryNames[$i] = $applianceRes[$i]->CategoryName;
	$pinLocations[$i] = $applianceRes[$i]->PinLocation;
}


date_default_timezone_set("Asia/Bangkok");
$dt = new DateTime();
$dateNow = $dt->format('l, j F Y');
$lastUpdate = "";
$categoryRes = DB::table('category')
				->select('CategoryID')
				->orderby('CategoryID','asc')
				->get();
for( $i=0;$i<count($categoryRes);$i++ )
{
	$posCategoryID[$i] = $categoryRes[$i]->CategoryID;

	$volRes = WattageUsage::where('UpdateDate', '=', $dateNow)
				->where('CategoryID', '=', $posCategoryID[$i])
				->get();
	if(count($volRes) <= 0)
	{
		$volValue[$i] = 0;
	}
	else
	{
		$volValue[$i] = $volRes[0]->Value;
		$lastUpdate = $volRes[0]->UpdateTime;
	}

}

$categoryIDsF = json_encode($categoryIDs);
$file = "tmp/categoryIDs.txt";
$bytes_written = File::put($file, $categoryIDsF);

$posCategoryIDF = json_encode($posCategoryID);
$file = "tmp/posCategoryID.txt";
$bytes_written = File::put($file, $posCategoryIDF);

$pinLocationsF = json_encode($pinLocations);
$file = "tmp/pinLocations.txt";
$bytes_written = File::put($file, $pinLocationsF);

?>

<h3>Background Process</h3>
<table>
	<tr>
		<td width="200px" >
			Date Now
		</td>
		<td>
			:
		</td>
		<td id="dateNow">
			Loading date...
		</td>
	</tr>
	<tr>
		<td>
			Time Now
		</td>
		<td>
			:
		</td>
		<td id="timeNow">
			Loading time...
		</td>
	</tr>
	<tr>
		<td>
			All CategoryIDs
		</td>
		<td>
			:
		</td>
		<td id="poscatid">
			Loading data...
		</td>
	</tr>
	<tr>
		<td>
			Active CategoryIDs
		</td>
		<td>
			:
		</td>
		<td id="categoryid">
			Loading data...
		</td>
	</tr>
	<tr>
		<td>
			Current GPIO States
		</td>
		<td>
			:
		</td>
		<td id="states">
			Loading data...
		</td>
	</tr>
	<tr>
		<td>
			Active PinLocations
		</td>
		<td>
			:
		</td>
		<td id="pinLocations">
			Loading data...
		</td>
	</tr>
	<tr>
		<td>
			Current Voltage Usage
		</td>
		<td>
			:
		</td>
		<td id="value">
			Loading data...
		</td>
	</tr>
	<tr>
		<td>
			Background Process Running
		</td>
		<td>
			:
		</td>
		<td id="runningTime">
			Loading data...
		</td>
	</tr>
	<tr>
		<td>
			Last Update Database Data
		</td>
		<td>
			:
		</td>
		<td id="updateTime">
			{{ ($lastUpdate != null &&  $lastUpdate != "" ) ? $lastUpdate : 'Not Updated Yet...' }}
		</td>
	</tr>
</table>

@include('javascript.indigoJS')
{{ HTML::script('assets/js/jquery-1.10.2.js'); }}
<script type="text/javascript">

var timer;

function getCategoryIDs()
{
	var File = new XMLHttpRequest();
	var res = "";
	File.open("GET", " {{ (URL::to('/tmp/categoryIDs.txt'))}} ", false);
	var allText = "";
	var wattageUsage = null;
	File.onreadystatechange = function ()
	{
	    if(File.readyState === 4)
	    {
	        if(File.status === 200 || File.status == 0)
	        {
	            allText = File.responseText;
	            res = JSON.parse(allText);
	        }
	    }
	}
	File.send(null);
	return allText;
}

function getPosCatID()
{
	var File = new XMLHttpRequest();
	var res = "";
	File.open("GET", " {{ (URL::to('/tmp/posCategoryID.txt'))}} ", false);
	var allText = "";
	var wattageUsage = null;
	File.onreadystatechange = function ()
	{
	    if(File.readyState === 4)
	    {
	        if(File.status === 200 || File.status == 0)
	        {
	            allText = File.responseText;
	            //console.log(allText);
	            res = JSON.parse(allText);
	        }
	    }
	}
	File.send(null);
	return allText;
}

function getPinLocations()
{
	var File = new XMLHttpRequest();
	var res = "";
	File.open("GET", " {{ (URL::to('/tmp/pinLocations.txt'))}} ", false);
	var allText = "";
	var wattageUsage = null;
	File.onreadystatechange = function ()
	{
	    if(File.readyState === 4)
	    {
	        if(File.status === 200 || File.status == 0)
	        {
	            allText = File.responseText;
	            //console.log(allText);
	            res = JSON.parse(allText);
	        }
	    }
	}
	File.send(null);
	return allText;
}

function resetVolValues()
{
	for(var i=0;i<volValue.length;i++)
	{
		volValue[i] = 0;
	}
}

function startLoop()
{
	counter = 0;
	volValue = <?= json_encode($volValue) ?>;
	var res;
	dateNow = getDateNow();
    timer = setInterval(function()
    {
		activeCatIDs = JSON.parse(getCategoryIDs());
		posCategoryID = JSON.parse(getPosCatID());
		pinLocations = JSON.parse(getPinLocations());
		currVal = getCurrentValue();
		var states = getGPIOValue();
		if(dateNow != getDateNow())
		{
			resetVolValues();
		}

		dateNow = getDateNow();
		document.getElementById('states').innerText = states;
    	document.getElementById('poscatid').innerText = posCategoryID;
    	document.getElementById('categoryid').innerText = activeCatIDs;
    	document.getElementById('pinLocations').innerText = pinLocations;
    	document.getElementById('runningTime').innerText = convertTime(counter);
    	document.getElementById('timeNow').innerText = getTimeNow();
    	document.getElementById('dateNow').innerText = dateNow;

		for(var i=0;i<activeCatIDs.length;i++)
		{
			for(var j=0;j<posCategoryID.length;j++)
			{
				if(posCategoryID[j] == activeCatIDs[i])
				{
					if (volValue[j] == null)
					{
						volValue[j] = 0;
					}
					if(states[pinLocations[i]] == "1")
					{
						volValue[j] += parseInt(currVal[pinLocations[i]]);
					}
					break;
				}
			}
		}
		document.getElementById('value').innerHTML = volValue;
     	counter++;
     	if (counter%10 == 0)
        {
        	// what to do after 5 minutes
        	jQuery.ajax({
		      type: "POST",
		      url: "{{URL::to('admin/insertWattageUsage')}}",
		      data : {wattageUsage : JSON.stringify(volValue), categoryIDs : JSON.stringify(posCategoryID), dateNow : JSON.stringify(getDateNow()), timeNow : JSON.stringify(getTimeNow())},
		      dataType: 'json',
		      success: function(res) {
		        if (res!= null)
		        {
		            console.log(res);
		            document.getElementById('updateTime').innerHTML = getTimeNow();
		        }
		      },
		      complete: function() {
		            //setTimeout(getSerialData(temp),500); //After completion of request, time to redo it after a second
		         }
    		});
        }
    }, 1000);
}

startLoop();
</script>