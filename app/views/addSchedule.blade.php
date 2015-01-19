@extends('layout.BaseTemplate')

@section('title')
	Add Schedule
@stop

@section('content')
<div class="panel panel-default">
	<div class="panel-heading headerFont">
		Schedule
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<form action=" {{ URL::route('addSchedule-post') }} " method="post">
					<div class="form-group @if ($errors->has('applianceID')) has-error @endif">
						<label>Appliance Name</label>
                        <select class="form-control" name="applianceID">
                        	<option value="" {{ (!Input::old('applianceID')) ? 'selected=""' : '' }} disabled="">- Appliance Name -</option>
                        <?php
                        	foreach($appliances as $appliance)
                        	{
                        ?>
                            <option value="{{ $appliance->ApplianceID }}" {{ ( (Input::old('applianceID') != null && Input::old('applianceID')!= "") && (Input::old('applianceID')) == $appliance->ApplianceID) ? 'selected=""' : '' }}  >{{$appliance->ApplianceName."  - in ".$appliance->RoomName}}</option>
                        <?php
                        	}
                        ?>
                        </select>
                        @if ($errors->has('applianceID'))
						<label class="control-label" for="inputError">
							@if (strpos($errors->first('applianceID'),'required') !== false)
								Appliance must be chosen
							@endif
						</label> 
						@endif
					</div>
					<div class="form-group @if ($errors->has('hour') || $errors->has('minute')) has-error @endif row">
						<div style="margin-left:15px;">
							<label>Time {{ Input::old('hour') }} </label>
						</div>
						<div class="col-md-4">
							<select class="form-control" name="hour">
	                        	<option value="" {{ (!Input::old('hour')) ? 'selected=""' : '' }} disabled="">- Hour -</option>
	                        <?php
	                        	for($i=0;$i<=23;$i++)
	                        	{
	                        ?>
	                            <option value="{{ ($i < 10) ? '0'.$i : $i }}" {{ ( (Input::old('hour') != null && Input::old('hour')!= "") && (Input::old('hour')) == $i) ? 'selected=""' : '' }} >{{ ($i < 10) ? '0'.$i : $i }}</option>
	                        <?php
	                        	}
	                        ?>
	                        </select>
	                    </div>
	                    <div class="col-md-4">
	                        <select class="form-control" name="minute">
	                        	<option value="" {{ (!Input::old('minute')) ? 'selected=""' : '' }} disabled="">- Minute -</option>
	                        <?php
	                        	for($i=0;$i<=59;$i++)
	                        	{
	                        ?>
	                            <option value="{{ ($i < 10) ? '0'.$i : $i }}" {{ ( (Input::old('minute') != null && Input::old('minute')!= "") && (Input::old('minute')) == $i) ? 'selected=""' : '' }}  >{{ ($i < 10) ? '0'.$i : $i }}</option>
	                        <?php
	                        	}
	                        ?>
	                        </select>
	                    </div>
	                    <div style="margin-left:15px"> 
                        @if ($errors->has('hour') || $errors->has('minute'))
						<label class="control-label" for="inputError">
							Time hour and minute must be chosen
						</label> 
						@endif
						</div>
					</div>
					<div class="form-group @if ($errors->has('toState')) has-error @endif">
						<label>To Do</label>
						<select name="toState" class="form-control">
							<option value="1" {{ (Input::old('toState') == "1") ? 'selected=""' : '' }} >Turn On</option>
							<option value="0" {{ (Input::old('toState') == "0") ? 'selected=""' : '' }}>Turn Off</option>
						</select>
					</div>
					@if ($errors->has('toState'))
						<label class="control-label" for="inputError">
							To do must be chosen
						</label> 
					@endif
					{{ Form::token() }}
					<div class="form-group">
						<input type="submit" class="btn btn-success" value="Add Schedule"></input>
		                <input type="reset" class="btn btn-md btn-danger"></input>
		            </div>
				</form>
			</div>
		</div>
	</div>
</div>
@stop