<script type="text/javascript">

function isNumeric(obj) {
    return !isNaN(parseFloat(obj)) && isFinite(obj);
}

function getTimeNow()
{
  var d = new Date();
  var hours   = d.getHours();
  var minutes = d.getMinutes();
  var seconds = d.getSeconds();
  if (hours   < 10) {hours   = "0"+hours;}
  if (minutes < 10) {minutes = "0"+minutes;}
  if (seconds < 10) {seconds = "0"+seconds;}
  return hours+":"+minutes+":"+seconds;
}

function getDateNow()
{
  var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
  var days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
  var d = new Date();
  return days[d.getDay()]+", "+d.getDate()+" "+months[d.getMonth()]+" "+d.getFullYear();
}

function getDatabaseFormattedDate()
{
  var d = new Date();
  var month = d.getMonth()+1;
  var date = d.getDate();
  if (month<10) { month = "0"+month; }
  if (date<10) { date = "0"+date; }

  return d.getFullYear()+"-"+month+"-"+date;
}

function convertTime(sec_num)
{
  var hours   = Math.floor(sec_num / 3600);
  var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
  var seconds = sec_num - (hours * 3600) - (minutes * 60);

  if (hours   < 10) {hours   = "0"+hours;}
  if (minutes < 10) {minutes = "0"+minutes;}
  if (seconds < 10) {seconds = "0"+seconds;}
  return hours+' hour(s) '+minutes+' minute(s) '+seconds+' second(s)';
}

function sleep(milliseconds) {
  var start = new Date().getTime();
  for (var i = 0; i < 1e7; i++) {
    if ((new Date().getTime() - start) > milliseconds){
      break;
    }
  }
}

function changeState(pin_in,currStatus)
{
    clearInterval(timer);
    $.post("{{URL::to('appliances/changeState')}}",{pin:pin_in, from_status:currStatus}, function(res){
      console.log(res);
      startLoop();
    });
}

function showDialog(message)
{
  BootstrapDialog.show({
    type : BootstrapDialog.TYPE_DANGER,
    title : 'Warning',
        message: message,
        buttons : [{
          label : 'Ok',
          action : function(dialogItself)
          {
            dialogItself.close();
          }
        }]
    });
}

function turnOnState()
{
  var pinLocation = document.getElementById('pinLocationTxt').value;
  //alert(pinLocation);
  if(pinLocation == null || pinLocation == "")
  {
    showDialog("Pin Location must be filled before turn on pin");
  }
  else if (!isNumeric(pinLocation))
  {
    showDialog("Pin Location must be numeric");
  }
  else
  {
    console.log("asd "+pinLocation);
    changeState(pinLocation,0);
  }
}

function getCallibrateData(state)
{
  var pinLocation = document.getElementById('pinLocationTxt').value;
  //alert(pinLocation);
  if(pinLocation == null || pinLocation == "")
  {
    showDialog("Pin Location must be filled before callibrate");
  }
  else if (!isNumeric(pinLocation))
  {
    showDialog("Pin Location must be numeric");
  }
  else
  {
    var states = getGPIOValue();
    if (states[pinLocation] == null || states[pinLocation] != "1")
    {
      showDialog("Pin must be turned on before callibrate");
    }
    else if (states[pinLocation] == "1")
    {
      var currentValues = getCurrentValue();
      var callibrateValue = currentValues[pinLocation];
      if (state == "on")
      {
        jQuery('#onPower').val(callibrateValue);
      }
      else if (state == "standby")
      {
        jQuery('#standbyPower').val(callibrateValue);
      }
    } 
  }
}

function getCurrentValue()
{
  var currentValueFile = new XMLHttpRequest();
  var res = "";
    currentValueFile.open("GET", " {{ (URL::to('/tmp/currentValue.txt'))}} ", false);
    currentValueFile.onreadystatechange = function ()
    {
        if(currentValueFile.readyState === 4)
        {
            if(currentValueFile.status === 200 || currentValueFile.status == 0)
            {
                var allText = currentValueFile.responseText;
                //console.log(allText);
                res = JSON.parse(allText);
            }
        }
    }
    currentValueFile.send(null);
    return res;
}

function getGPIOValue()
{
  var gpioFile = new XMLHttpRequest();
  var gpioRes = "";
    gpioFile.open("GET", " {{ (URL::to('/tmp/currentGPIO.txt'))}} ", false);
    var gpioRes = "";
    gpioFile.onreadystatechange = function ()
    {
        if(gpioFile.readyState === 4)
        {
            if(gpioFile.status === 200 || gpioFile.status == 0)
            {
                var gpioData = gpioFile.responseText;
                gpioRes = JSON.parse(gpioData);
            }
        }
    }
    gpioFile.send(null);
    return gpioRes;
}

function updateStateStatus()
{
  on_str = "<button type=\"button\" class=\"btn btn-success\">On</button>";
  off_str = "<button type=\"button\" class=\"btn btn-danger\" onclick=\"turnOnState()\">Turn On</button>";

  var pinLocation = document.getElementById('pinLocationTxt').value;
  if(pinLocation == null || pinLocation == "" || (!isNumeric(pinLocation)))
  {
    res_str = off_str;
  }
  else
  {
    var states = getGPIOValue();
    if (states[pinLocation] == null || states[pinLocation] != "1")
    {
      res_str = off_str;
    }
    else if (states[pinLocation] == "1")
    {
      res_str = on_str;
    }   
  }
  jQuery('#turnOnButton').html(res_str);
}

</script>