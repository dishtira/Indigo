function refreshState(temp)
{
    on_str = "<button id=\"btn-state\" class=\"btn btn-success \" style=\"margin-left:5px;\" type=\"button\">On</button>";
    off_str = "<button id=\"btn-state\" class=\"btn btn-danger  \" style=\"margin-left:5px;\" type=\"button\">Off</button>";
    standby_str = "<button id=\"btn-state\" class=\"btn btn-warning \" style=\"margin-left:5px;\" type=\"button\">Standby</button>";
    err_str = "<button id=\"btn-state\" class=\"btn btn-danger blink\" style=\"margin-left:5px;\" type=\"button\"><i class=\"fa fa-warning\"></i> Unplugged</button>";
    console.log(temp);
    jQuery.ajax({
          type: "POST",
          url: '{{ URL::route('getState') }}',
          data : { pinLocations : JSON.stringify(temp) },
          dataType: 'json',
          success: function(res) {
            if(res !=null)
            {
                console.log(res);
            }
          },
          complete: function() {
             
            }
    });
}