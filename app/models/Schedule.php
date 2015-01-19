<?php

class Schedule extends Eloquent {
	public $timestamps = false;
	protected $fillable = array('ScheduleID',
								'ApplianceID',
								'Time',
								'ToState',
								'Status'
							);

	protected $primaryKey = 'ScheduleID';

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'schedule';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

}
