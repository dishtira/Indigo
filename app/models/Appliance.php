<?php

class Appliance extends Eloquent {

	public $timestamps = false;
	protected $fillable = array('ApplianceID','RoomID', 'CategoryID','ApplianceName','OnPower','StandbyPower','OffPower','PinLocation');
	protected $primaryKey = 'ApplianceID';

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'appliance';

	protected function generateID()
	{
		$appliance = DB::table('appliance')
						->select('ApplianceID')
						->distinct()
						->orderBy('ApplianceID','desc')
						->first();

		if (count($appliance) <= 0)
		{
			$newID = "AP000";
		}
		else
		{
			$newLastID = substr($appliance->ApplianceID, 2,3);
			$newLastID = ($newLastID+1);

			if ($newLastID<10)
			{
			    $newLastID = "00".$newLastID;
			}
			else if ($newLastID<100)
			{
			    $newLastID = "0".$newLastID;
			}
			$newID = "AP".$newLastID;
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
