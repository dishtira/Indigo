@extends('layout.BaseTemplate')

@section('title')
	Edit User
@stop

@section('content')
<div class="panel panel-default">
	<div class="panel-heading headerFont">
		Users
	</div>
	<div class="panel-body">
		<div class="panel-group" id="accordion">
			<div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" id="changeRole" data-parent="#accordion" href="#changeRoleTarget" class="collapsed">Change Role</a>
                    </h4>
                </div>
                <div id="changeRoleTarget" class="panel-collapse in" style="height: auto;">
                    <div class="panel-body">
                        <div class="row">
							<div class="col-md-6">
								<form action=" {{ URL::route('editUser-post') }} " method="post">
									<input type="hidden" name="status" value="role" />
									<div class="form-group @if ($errors->has('username') && $errors->first('location') == 'editUser') has-error @endif">
										<label>Username</label>
										<input type="hidden" name="username" value="{{ $user->Username }}" >
										<input class="form-control" placeholder="Username" disabled="" value="{{ (Input::old('username')) ? e(Input::old('username')) : $user->Username }}" />
										@if ($errors->has('username') && $errors->first('location') == 'editUser')
										<label class="control-label" for="inputError">
											@if (strpos($errors->first('username'),'required') !== false)
												Username must be filled
											@elseif(strpos($errors->first('username'),'already taken') !== false)
												Username already exists
											@endif
										</label> 
										@endif
									</div>
									<div class="form-group @if ($errors->has('role')) has-error @endif">
				                        <label>Role</label>
				                        <select class="form-control" name="role">
				                        	<option value="" disabled="">- Roles -</option>
				                        <?php
				                        	$roles = ["user","guest"];
				                        	foreach($roles as $role)
				                        	{
				                        ?>
				                            <option value="{{ $role }}" {{ ($user->Role == $role) ? 'selected=""' : '' }} >{{$role}}</option>
				                        <?php
				                        	}
				                        ?>
				                        </select>
				                        @if ($errors->has('role'))
										<label class="control-label" for="inputError">
											@if (strpos($errors->first('role'),'required') !== false)
												Role must be chosen
											@endif
										</label> 
										@endif
				                    </div>
									<div class="form-group">
										<input type="submit" class="btn btn-success" value="Update Role"></input>
						                <input type="reset" class="btn btn-md btn-danger"></input>
						            </div>
						            {{ Form::token() }}
								</form>
							</div>
						</div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" id="changePassword" data-parent="#accordion" href="#changePasswordTarget" class="collapsed">Change Password</a>
                    </h4>
                </div>
                <div id="changePasswordTarget" class="panel-collapse collapse" style="height: auto;">
                    <div class="panel-body">
						<div class="row">
							<div class="col-md-6">
								<form action=" {{ URL::route('editUser-post') }} " method="post">
									<input type="hidden" name="status" value="password" />
									<div class="form-group @if ($errors->has('username') && $errors->first('location') == 'editUser') has-error @endif">
										<label>Username</label>
										<input type="hidden" name="username" value="{{ $user->Username }}" >
										<input class="form-control" placeholder="Username" disabled="" value="{{ (Input::old('username')) ? e(Input::old('username')) : $user->Username }}" />
										@if ($errors->has('username') && $errors->first('location') == 'editUser')
										<label class="control-label" for="inputError">
											@if (strpos($errors->first('username'),'required') !== false)
												Username must be filled
											@elseif(strpos($errors->first('username'),'already taken') !== false)
												Username already exists
											@endif
										</label> 
										@endif
									</div>
									<div class="form-group @if ($errors->has('password') && $errors->first('location') == 'editUser') has-error @endif">
										<label>Password</label>
										<input class="form-control" name="password" placeholder="Password" value="" type="password" />
										@if ($errors->has('password') && $errors->first('location') == 'editUser')
										<label class="control-label" for="inputError">
											@if(strpos($errors->first('password'),'required') !== false)
												Password must be filled
											@elseif(strpos($errors->first('password'),'must match') !== false)
												Password and confirm password must be the same
											@endif
										</label> 
										@endif
									</div>
									<div class="form-group @if ($errors->has('confirmPassword') && $errors->first('location') == 'editUser') has-error @endif">
										<label>Confirm Password</label>
										<input class="form-control" name="confirmPassword" placeholder="Confirm Password" value="" type="password" />
										@if ($errors->has('confirmPassword') && $errors->first('location') == 'editUser')
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
										<input type="submit" class="btn btn-success" value="Change Password"></input>
						                <input type="reset" class="btn btn-md btn-danger"></input>
						            </div>
						            {{ Form::token() }}
								</form>
							</div>
						</div>
                    </div>
                </div>
            </div>
		</div>
	</div>
</div>
@stop