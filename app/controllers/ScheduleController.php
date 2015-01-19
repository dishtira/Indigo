<?php

class ScheduleController extends IndigoController{

	public function showSchedules()
	{
		$schedules = Schedule::join('Appliance', 'Schedule.ApplianceID', '=', 'Appliance.ApplianceID')
						->join('Room', 'Appliance.RoomID', '=', 'Room.RoomID')
						->get();
		return $this->showPageWith('scheduling', array(
			'schedules' => $schedules
		));
	}

	public function showAddSchedule()
	{
		$appliances = Appliance::join('Room', 'Appliance.RoomID', '=', 'Room.RoomID')
						->get();

		return $this->showPageWith('addSchedule', array(
			'appliances' => $appliances
		));
	}

	public function addSchedule()
	{
		$validator = Validator::make(Input::all(), array(
			'applianceID' 	=> 'required',
			'hour' 			=> 'required',
			'minute'		=> 'required',
			'toState'		=> 'required'
		));

		if ($validator->fails())
		{
			return Redirect::route('addSchedule-show')
						->withErrors($validator)
						->withInput();
		}
		else
		{

			$applianceID = Input::get('applianceID');
			$time = Input::get('hour').":".Input::get('minute');
			$toState = Input::get('toState');

			$create = Schedule::create(array(
					'ApplianceID' => $applianceID,
					'Time' => $time,
					'ToState' => $toState
				));
			if($create)
			{
				return Redirect::route('scheduling');
			}
			else
			{
				return "fail add schedule";
			}
		}
	}

	public function activateSchedule($scheduleID = null, $action = null)
	{
		if ($action == "activate")
		{
			$status = "1";
		}
		else if ($action == "deactivate")
		{
			$status = "0";
		}

		$schedule = Schedule::where('ScheduleID', '=', $scheduleID)->first();
		$schedule->Status = $status;
		$schedule->save();

		return Redirect::route('scheduling');

	}

	public function showEditSchedule($scheduleID = null)
	{
		$schedule = Schedule::where('ScheduleID', '=', $scheduleID)->first();
		$appliances = Appliance::join('Room', 'Appliance.RoomID', '=', 'Room.RoomID')
						->get();
		return $this->showPageWith('editSchedule', array(
			'schedule' => $schedule,
			'appliances' => $appliances
		));
	}

	public function editSchedule()
	{
		$validator = Validator::make(Input::all(), array(
			'applianceID' 	=> 'required',
			'hour' 			=> 'required',
			'minute'		=> 'required',
			'toState'		=> 'required'
		));

		if ($validator->fails())
		{
			return Redirect::to('schedule/editSchedule/'.Input::get('scheduleID'))
						->withErrors($validator)
						->withInput();
		}
		else
		{
			$applianceID = Input::get('applianceID');
			$time = Input::get('hour').":".Input::get('minute');
			$toState = Input::get('toState');
			$scheduleID = Input::get('scheduleID');
			
			$schedule = Schedule::where('ScheduleID', '=', $scheduleID)->first();
			$schedule->ApplianceID = $applianceID;
			$schedule->Time = $time;
			$schedule->ToState = $toState;
			$schedule->save();

			return Redirect::route('scheduling');
		}
	}

	public function deleteSchedule($scheduleID = null)
	{
		$schedule = Schedule::where('ScheduleID', '=', $scheduleID)->delete();
		return Redirect::route('scheduling');
	}
}