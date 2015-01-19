<?php

class WattageUsage extends Eloquent {
	public $timestamps = false;
	protected $fillable = array('VoltageID','CategoryID','Value','UpdateDate', 'UpdateTime');
	protected $primaryKey = 'VoltageID';

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'wattageusage';

	protected function generateID()
	{
		$voltage = DB::table('wattageusage')
						->select('VoltageID')
						->distinct()
						->orderBy('VoltageID','desc')
						->first();

		$newLastID = substr($voltage->VoltageID, 2,3);
		$newLastID = ($newLastID+1);

		if ($newLastID<10)
		{
		    $newLastID = "00".$newLastID;
		}
		else if ($newLastID<100)
		{
		    $newLastID = "0".$newLastID;
		}
		$newID = "VT".$newLastID;

		return $newID;
	}

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

}
