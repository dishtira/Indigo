<?php

class Room extends Eloquent {
	public $timestamps = false;
	protected $fillable = array('RoomID','RoomName');
	protected $primaryKey = 'RoomID';

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'room';

	protected function generateID()
	{
		$room = DB::table('room')
						->select('RoomID')
						->distinct()
						->orderBy('RoomID','desc')
						->first();
		if (count($room) <= 0)
		{
			$newID = "RM000";
		}
		else
		{
			$newLastID = substr($room->RoomID, 2,3);
			$newLastID = ($newLastID+1);

			if ($newLastID<10)
			{
			    $newLastID = "00".$newLastID;
			}
			else if ($newLastID<100)
			{
			    $newLastID = "0".$newLastID;
			}
			$newID = "RM".$newLastID;
		}

		return $newID;
	}

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

}
