@extends('layout.BaseTemplate')

@section('title')
	<?php echo $room->RoomName ?>
@stop

@section('content')
	<div class="panel panel-default">
        <div class="panel-heading headerFont">
             Appliances
        </div>
        <div class="panel-body">
            <div class="table-responsive">
            @if ( $appliances == null || $appliances->count() <=0 )
            <div class="form-group">
                <label>There is no appliance yet in this room</label>
            </div>
            @else
                <table class="table table-striped table-bordered table-hover" id="dataTables">
                    <thead>
                        <tr>  
                            <th>Appliance Name</th>
                            <th>State</th>
                            <th>Current Electrical Value</th>
                            @if(Auth::user()->Role != "guest")
                            <th>Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $i = 0;
                        foreach($appliances as $appliance)
                        {
                            $pinLocations[$i] = $appliance->PinLocation;
                            $onPower[$i] = $appliance->OnPower;
                            $standbyPower[$i] = $appliance->StandbyPower;                            
                    ?>
                        <tr class="odd gradeX">
                            <td> <?php echo $appliance->ApplianceName ?></td>
                            <td>
                               <!-- <div id="state_div<?= $i ?>" onclick="changeState(<?= $appliance->PinLocation ?>)"> -->
                               <div id="state_div<?= $i ?>" onclick="changeState(<?= $appliance->PinLocation ?>, null)">
                                    State
                                </div>
                            </td>
                            <td>
                                <span id="serialData<?= $i ?>" class="">
                                    Reading Current Serial Value...
                                </span>
                            </td>
                            @if(Auth::user()->Role != "guest")
                            <td>
                                <a href="{{ URL::to('appliances/editAppliance/'.$appliance->ApplianceID) }}"><button class="btn btn-primary"><i class="fa fa-edit"></i> Edit</button></a>
                                <a href="{{ URL::to('appliances/deleteAppliance/'.$appliance->ApplianceID) }}"> <button class="btn btn-danger"><i class="fa fa-eraser"></i> Delete</button></a>
                            </td>
                            @endif
                        </tr>
                    <?php
                            $i++;
                        }
                    ?>                                           
                    </tbody>
                </table>
                @endif
                @if(Auth::user()->Role != "guest")
                <a href="{{ URL::to('appliances/addAppliance/'.$room->RoomID) }}"><button type="submit" class="btn btn-success"><i class="fa fa-plus-square"></i> Add Appliance</button></a>
                @endif
            </div>
        </div>
    </div>
    <label class="blink"> </label>
@stop

@section('javascript')
@if ( $appliances == null || $appliances->count() <=0 )
    There is no appliance yet in this room
@else
<script>
$(document).ready(function () {
    $('#dataTables').dataTable();
});

function blnk() {
    $(".blink").css({opacity: 0}).
    animate({opacity: 1}, 500, "linear").
    animate({opacity: 0}, 500, "linear");
}


var timer;

function startLoop()
{
    timer = setInterval(function()
    {
        updateAppliancesData();
        blnk();
    }, 1000);
}

startLoop();

function updateAppliancesData()
{
	var res = getCurrentValue();
	var gpioRes = getGPIOValue();

    temp = <?php echo json_encode($pinLocations)?>;

    for(i = 0;i<temp.length;i++){
        if(gpioRes[temp[i]] == "0")
        {
            res[temp[i]] = "0";
        }
        jQuery('#serialData'+i).html(res[temp[i]]+" VA");
    }
    standByPowerTemp = <?php echo json_encode($standbyPower)?>;
    onPowerTemp = <?php echo json_encode($onPower)?>;

    on_str = "<button id=\"btn-state\" class=\"btn btn-success \" style=\"margin-left:5px;\" type=\"button\">On</button>";
    off_str = "<button id=\"btn-state\" class=\"btn btn-danger  \" style=\"margin-left:5px;\" type=\"button\">Off</button>";
    standby_str = "<button id=\"btn-state\" class=\"btn btn-warning \" style=\"margin-left:5px;\" type=\"button\">Standby</button>";
    err_str = "<button id=\"btn-state\" class=\"btn btn-danger blink\" style=\"margin-left:5px;\" type=\"button\"><i class=\"fa fa-warning\"></i> Unplugged</button>";
    for(i = 0;i<temp.length;i++){
        stateRes = err_str;
        diff = parseFloat(standByPowerTemp[i])*20/100;
        diffOn = parseFloat(onPowerTemp[i])*15/100;
        currVal = parseFloat(res[temp[i]]);
        if(res[temp[i]] > "0" && currVal >= (parseFloat(onPowerTemp[i])-diffOn))
        {
          stateRes = on_str;
        }
        else if (res[temp[i]] > "0" && (currVal >= (parseFloat(standByPowerTemp[i])-diff) &&  currVal < (parseFloat(onPowerTemp[i])-diffOn)) )
        {
          stateRes = standby_str;
        }
        else if(res[temp[i]] > "0" && currVal < (parseFloat(standByPowerTemp[i])-diff))
        {
          stateRes = err_str;
        }
        else if (res[temp[i]] == "0")
        {
          stateRes = off_str;
        }
        //console.log(res[temp[i]] + " standby " + standByPowerTemp[i] + " currVal "+currVal);
        jQuery('#state_div'+i).html(stateRes);
    }
            
}

@endif
</script>
@stop