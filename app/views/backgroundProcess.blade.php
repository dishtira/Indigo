<?php

$applianceRes = DB::table('appliance')
			->join('category', 'appliance.CategoryID', '=', 'category.CategoryID')
			->select('appliance.CategoryID','category.CategoryName', 'appliance.PinLocation')
			->orderby('appliance.PinLocation','asc')
			->get();
$categoryIDs = null;
for( $i=0;$i<count($applianceRes);$i++)
{
	$categoryIDs[$i] = $applianceRes[$i]->CategoryID;
	$categoryNames[$i] = $applianceRes[$i]->CategoryName;
	$pinLocations[$i] = $applianceRes[$i]->PinLocation;
}


date_default_timezone_set("Asia/Bangkok");
$dt = new DateTime();
$dateNow = $dt->format('Y-m-j');
//$dateNow = $dt->format('l, j F Y');
$lastUpdate = "";
$categoryRes = DB::table('category')
				->select('CategoryID')
				->orderby('CategoryID','asc')
				->get();
$posCategoryID = null;
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

if($categoryIDs == null)
{
	$categoryIDs = "";
	$pinLocations = "";
}

if($posCategoryID == null)
{
	$posCategoryID = "";
	$volValue = "";
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

function toFixed(value, precision) {
    var precision = precision || 0,
        power = Math.pow(10, precision),
        absValue = Math.abs(Math.round(value * power)),
        result = (value < 0 ? '-' : '') + String(Math.floor(absValue / power));

    if (precision > 0) {
        var fraction = String(absValue % power),
            padding = new Array(Math.max(precision - fraction.length, 0) + 1).join('0');
        result += '.' + padding + fraction;
    }
    return result;
}

function checkSchedule(currentTime)
{
	jQuery.ajax({
      type: "POST",
      url: "{{URL::to('schedules/getScheduleData')}}",
      data : {currTime : JSON.stringify(currentTime)},
      dataType: 'json',
      success: function(res) {
        if (res!= null)
        {
            for(var i=0;i<res.length;i++)
            {
            	console.log("Pin : "+res[i][0]);
            	console.log("state : "+res[i][1]);
            	changeStateSchedule(res[i][0], res[i][1]);
            	sleep(1000);
            }
        }
      },
      complete: function() {
            //setTimeout(getSerialData(temp),500); //After completion of request, time to redo it after a second
         }
	});
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
		states = getGPIOValue();
		if(dateNow != getDateNow())
		{
			resetVolValues();
		}

		dateNow = getDateNow();
		timeNow = getTimeNow();
		document.getElementById('states').innerText = states;
    	document.getElementById('poscatid').innerText = posCategoryID;
    	document.getElementById('categoryid').innerText = activeCatIDs;
    	document.getElementById('pinLocations').innerText = pinLocations;
    	document.getElementById('runningTime').innerText = convertTime(counter);
    	document.getElementById('timeNow').innerText = timeNow;
    	document.getElementById('dateNow').innerText = dateNow;

    	if(posCategoryID.length <= 0 || posCategoryID == "")
    	{
    		volValue = "";
    	}
    	else
    	{
			for(var i=0;i<activeCatIDs.length;i++)
			{
				for(var j=0;j<posCategoryID.length;j++)
				{
					if(posCategoryID[j] == activeCatIDs[i])
					{
						if (volValue == "")
						{
							volValue = [];
						}
						if (volValue == null || volValue[j] == null || volValue[j] == "")
						{
							volValue[j] = 0;
						}
						if(states[pinLocations[i]] == "1")
						{
							volValue[j] = toFixed((parseFloat(volValue[j])+parseFloat(currVal[pinLocations[i]])),2);
						}
						break;
					}
				}
			}
		}
		document.getElementById('value').innerHTML = volValue;
     	counter++;
     	if (counter%10 == 0 && activeCatIDs.length > 0)
        {
        	// what to do after 5 minutes
        	jQuery.ajax({
		      type: "POST",
		      url: "{{URL::to('admin/insertWattageUsage')}}",
		      data : {wattageUsage : JSON.stringify(volValue), categoryIDs : JSON.stringify(posCategoryID), dateNow : JSON.stringify(getDatabaseFormattedDate()), timeNow : JSON.stringify(getTimeNow())},
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

        if (timeNow.substring(6,8) == "00")
        {
        	checkSchedule(timeNow.substring(0,5));
        }

    }, 1000);
}

startLoop();
</script>