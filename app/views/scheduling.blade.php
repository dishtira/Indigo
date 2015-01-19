@extends('layout.BaseTemplate')

@section('title')
	Scheduling
@stop

@section('content')
<div class="panel panel-default">
	<div class="panel-heading headerFont">
		Schedules
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
			    @if($schedules == null || $schedules->count() <=0 )
			    <div class="form-group">
			        <label>There is no schedule yet</label>
			    </div>
			    @else
			    <table class="table table-striped table-bordered table-hover" id="dataTablesSchedule">
			        <thead>
			            <tr>  
			                <th>ApplianceID</th>
			                <th>Appliance Name</th>
			                <th>Room</th>
			                <th>Time</th>
			                <th>To Do</th>
			                <th>Status</th>
			                <th>Activation Action</th>
			                <th>Action</th>
			            </tr>
			        </thead>
			        <tbody>
			        <?php
			            foreach ($schedules as $schedule)
			            {
			        ?>
			            <tr class="odd gradeX">
			                <td><?= $schedule->ApplianceID ?></td>
							<td><?= $schedule->ApplianceName ?></td>
							<td><?= $schedule->RoomName ?></td>
							<td><?= $schedule->Time ?></td>
							<td><?= ($schedule->ToState == "1") ? '<font color="green">Turn On</font>' : '<font color="red">Turn Off</font>' ?></td>
							<td>
								<?php
			                        if ($schedule->Status == "1")
			                        {
			                    ?>
			                    	<font color="green">
			                            Activated
			                        </font>
			                    <?php
			                        }
			                        else
			                        {
			                    ?>      
		                    		<font color="red">
			                            Deactivated
			                        </font>
			                    <?php
			                        }
			                    ?>
							</td>
			                <td>
			                	<?php
			                        if ($schedule->Status == "1")
			                        {
			                    ?>
			                            <a href="{{ URL::to('schedule/activateSchedule/'.$schedule->ScheduleID.'/deactivate') }}"><button class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> Deactivate</button></a>
			                    <?php
			                        }
			                        else
			                        {
			                    ?>      <a href="{{ URL::to('schedule/activateSchedule/'.$schedule->ScheduleID.'/activate') }}"><button class="btn btn-success"><i class="glyphicon glyphicon-ok"></i> Activate</button></a>

			                    <?php
			                        }
			                    ?>
			                </td>
			                <td>
			                    <a href="{{ URL::to('schedule/editSchedule/'.$schedule->ScheduleID) }}"><button class="btn btn-primary"><i class="fa fa-edit"></i> Edit</button></a>
			                    <a href="{{ URL::to('schedule/deleteSchedule/'.$schedule->ScheduleID) }}"> <button class="btn btn-danger"><i class="fa fa-eraser"></i> Delete</button></a>
			                </td>
			            </tr>
			        <?php

			            }
			        ?>                                           
			        </tbody>
			    </table>
			    @endif
			    <a href="{{ URL::route('addSchedule-show') }}"><button type="submit" class="btn btn-success"><i class="fa fa-plus-square"></i> Add Schedule</button></a>
			</div>
			</div>
		</div>
	</div>
</div>
@stop