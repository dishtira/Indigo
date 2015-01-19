@extends('layout.BaseTemplate')

@section('title')
	Register
@stop

@section('content')
<div class="panel panel-default">
	<div class="panel-heading headerFont">
		Registration
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<form action=" {{ URL::route('register-post') }} " method="post">
					<div class="form-group @if ($errors->has('username') && $errors->first('location') == 'register') has-error @endif">
						<label>Username</label>
						<input class="form-control" name="username" placeholder="Username" value="{{ (Input::old('username')) ? e(Input::old('username')) : '' }}" />
						@if ($errors->has('username') && $errors->first('location') == 'register')
						<label class="control-label" for="inputError">
							@if (strpos($errors->first('username'),'required') !== false)
								Username must be filled
							@elseif(strpos($errors->first('username'),'already taken') !== false)
								Username already exists
							@endif
						</label> 
						@endif
					</div>
					<div class="form-group @if ($errors->has('password') && $errors->first('location') == 'register') has-error @endif">
						<label>Password</label>
						<input class="form-control" name="password" placeholder="Password" value="" type="password" />
						@if ($errors->has('password') && $errors->first('location') == 'register')
						<label class="control-label" for="inputError">
							@if(strpos($errors->first('password'),'required') !== false)
								Password must be filled
							@elseif(strpos($errors->first('password'),'must match') !== false)
								Password and confirm password must be the same
							@endif
						</label> 
						@endif
					</div>
					<div class="form-group @if ($errors->has('confirmPassword') && $errors->first('location') == 'register') has-error @endif">
						<label>Confirm Password</label>
						<input class="form-control" name="confirmPassword" placeholder="Confirm Password" value="" type="password" />
						@if ($errors->has('confirmPassword') && $errors->first('location') == 'register')
						<label class="control-label" for="inputError">
							@if(strpos($errors->first('confirmPassword'),'required') !== false)
								Confirm password must be filled
							@elseif(strpos($errors->first('confirmPassword'),'must match') !== false)
								Password and confirm password must be the same
							@endif
						</label>
						@endif
					</div>
					<div class="form-group">
						<input type="submit" class="btn btn-success" value="Register"></input>
		                <input type="reset" class="btn btn-md btn-danger"></input>
		            </div>
		            {{ Form::token() }}
				</form>
			</div>
		</div>
	</div>
</div>
@stop