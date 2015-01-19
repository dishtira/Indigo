@extends('layout.BaseTemplate')

@section('title')
	Add Appliance
@stop

@section('content')
<div class="panel panel-default">
	<div class="panel-heading headerFont">
		<?php echo $room->RoomName ?>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<form action=" {{ URL::route('addAppliance-post') }} " method="post">
					<input type="hidden" name="roomID" value="<?= $room->RoomID ?>"/>
					<div class="form-group @if ($errors->has('categoryID')) has-error @endif">
                        <label>Category</label>
                        <select class="form-control" name="categoryID">
                        	<option value="" selected="" disabled="">- Category Name -</option>
                        <?php
                        	foreach($categories as $category)
                        	{
                        ?>
                            <option value="{{ $category->CategoryID }}">{{$category->CategoryName}}</option>
                        <?php
                        	}
                        ?>
                        </select>
                        @if ($errors->has('categoryID'))
						<label class="control-label" for="inputError">
							@if (strpos($errors->first('categoryID'),'required') !== false)
								Category must be chosen
							@endif
						</label> 
						@endif
                    </div>
					<div class="form-group @if ($errors->has('applianceID')) has-error @endif">
						<label>Appliance ID</label>
						<input type="hidden" name="applianceID" value="{{ $newID }}"/>
						<input class="form-control" placeholder="Appliance ID" value="{{ $newID }}" disabled="" />
						@if ($errors->has('applianceID'))<label class="control-label" for="inputError">Appliance ID must be filled</label> @endif
					</div>
					<div class="form-group  @if ($errors->has('pinLocation')) has-error @endif">
						<label>Pin Location</label>
						<input class="form-control" id="pinLocationTxt" name="pinLocation" placeholder="Pin Location" {{ (Input::old('pinLocation')) ? 'value="'. e(Input::old('pinLocation')) .'"' : '' }} />
						@if ($errors->has('pinLocation'))
						<label class="control-label" for="inputError">
							@if (strpos($errors->first('pinLocation'),'required') !== false)
								Pin Location must be filled
							@elseif (strpos($errors->first('pinLocation'),'number') !== false)
								Pin Location must be numeric
							@endif
						</label> 
						@endif
						<div id="turnOnButton">
	                        <button  type="button" class="btn btn-danger" onclick="turnOnState()">Turn On</button>
	                    </div>
					</div>
					<div class="form-group  @if ($errors->has('applianceName')) has-error @endif">
						<label>Appliance Name</label>
						<input class="form-control" name="applianceName" placeholder="Appliance Name" {{ (Input::old('applianceName')) ? 'value="'. e(Input::old('applianceName')) .'"' : '' }}/>
						@if ($errors->has('applianceName'))<label class="control-label" for="inputError">Appliance Name must be filled</label> @endif
					</div>
					<div class="form-group  @if ($errors->has('onPower')) has-error @endif">
						<label>On Power</label>
						<input class="form-control" id="onPower" name="onPower" placeholder="On Power" {{ (Input::old('onPower')) ? 'value="'. e(Input::old('onPower')) .'"' : '' }} />
						@if ($errors->has('onPower'))
						<label class="control-label" for="inputError">
							@if (strpos($errors->first('onPower'),'required') !== false)
								On Power must be callibrated
							@elseif (strpos($errors->first('onPower'),'number') !== false)
								On Power must be numeric
							@endif
						</label>
						@endif
						<div>
							<button type="button" class="btn btn-info" onclick="getCallibrateData('on')"><i class="fa fa-gear"></i> Callibrate</button>
						</div>
					</div>
					<div class="form-group  @if ($errors->has('standbyPower')) has-error @endif">
						<label>Standby Power</label>
						<input class="form-control" id="standbyPower" name="standbyPower" placeholder="Standby Power" {{ (Input::old('standbyPower')) ? 'value="'. e(Input::old('standbyPower')) .'"' : '' }} />
						@if ($errors->has('standbyPower'))
						<label class="control-label" for="inputError">
							@if (strpos($errors->first('standbyPower'),'required') !== false)
								Standby Power must be callibrated
							@elseif (strpos($errors->first('standbyPower'),'number') !== false)
								Standby Power must be numeric
							@endif
						</label>
						@endif
						<div>
							<button type="button" class="btn btn-info" onclick="getCallibrateData('standby')"><i class="fa fa-gear"></i> Callibrate</button>
						</div>
					</div>
					<div class="form-group">
						<input type="submit" class="btn btn-success" value="Add Appliance"></input>
		                <input type="reset" class="btn btn-md btn-danger"></input>
		            </div>

				</form>
			</div>
		</div>
	</div>
</div>
@stop

@section('javascript')
<script type="text/javascript">
var timer;

function startLoop()
{
    timer = setInterval(function()
        {
            updateStateStatus();
            //console.log("testing");
        }, 1000);
}

startLoop();

</script>
@stop