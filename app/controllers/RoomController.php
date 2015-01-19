<?php
class RoomController extends IndigoController{

	public function showAddRoom()
	{
		$newID = Room::generateID();
		return $this->showPageWith('addRoom', array(
			'newID' => $newID
		));
	}

	public function addRoom()
	{
		$validator = Validator::make(Input::all(), array(
			'roomID'	=> 'required|unique:room',
			'roomName'	=> 'required'
		));

		if($validator->fails())
		{
			return Redirect::route('addRoom-show')
					->withErrors($validator)
					->withInput();
		}
		else
		{
			$roomID = Input::get('roomID');
			$roomName = Input::get('roomName');

			$create = Room::create(array(
				'RoomID'	=> $roomID,
				'RoomName'	=> $roomName
			));

			if ($create)
			{
				return Redirect::route('adminPanel-show');
			}
			else
			{
				return "Failed Add Room";
			}

		}
	}

	public function showEditRoom($roomID = null)
	{
		$room = Room::where('RoomID','=',$roomID)->first();

		return $this->showPageWith('editRoom', array(
			'room' => $room
		));
	}

	public function editRoom()
	{
		$validator = Validator::make(Input::all(), array(
			'roomName'	=> 'required'
		));

		$roomID = Input::get('roomID');
		if($validator->fails())
		{
			return Redirect::to('rooms/manage/editRoom/'.$roomID)
					->withErrors($validator)
					->withInput();
		}
		else
		{
			$roomName = Input::get('roomName');

			$room = Room::where('RoomID', '=', $roomID)->first();
			$room->RoomName = $roomName;
			$room->save();

			return Redirect::route('adminPanel-show');	
		}
	}

	public function deleteRoom($roomID = null)
	{
		$room = Room::where('RoomID', '=', $roomID)->delete();
		$appliances = Appliance::where('RoomID', '=',$roomID)->delete();
		return Redirect::route('adminPanel-show');
	}
}