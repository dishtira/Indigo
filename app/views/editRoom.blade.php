@extends('layout.BaseTemplate')

@section('title')
	Edit Room
@stop


@section('content')
<div class="panel panel-default">
	<div class="panel-heading headerFont">
		Room
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<form action=" {{ URL::route('editRoom-post') }} " method="post">
					<div class="form-group @if ($errors->has('roomID')) has-error @endif">
						<label>Room ID</label>
						<input type="hidden" name="roomID" value="{{ (Input::old('roomID')) ? e(Input::old('roomID')) : $room->RoomID }}"/>
						<input class="form-control" placeholder="Room ID" value="{{ (Input::old('roomID')) ? e(Input::old('roomID')) : $room->RoomID }}" disabled="" />
						@if ($errors->has('roomID'))<label class="control-label" for="inputError">Room ID must be filled</label> @endif
					</div>
					<div class="form-group @if ($errors->has('roomName')) has-error @endif">
						<label>Room Name</label>
						<input class="form-control" name="roomName" placeholder="Room Name" value="{{ (Input::old('roomName')) ? e(Input::old('roomName')) : $room->RoomName }}" />
						@if ($errors->has('roomName'))<label class="control-label" for="inputError">Room Name must be filled</label> @endif
					</div>
					<div class="form-group">
						<input type="submit" class="btn btn-success" value="Update Room"></input>
		                <input type="reset" class="btn btn-md btn-danger"></input>
		            </div>
				</form>
			</div>
		</div>
	</div>
</div>
@stop