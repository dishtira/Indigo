<?php

class Category extends Eloquent {
	public $timestamps = false;
	protected $fillable = array('CategoryID','CategoryName');
	protected $primaryKey = 'CategoryID';

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'category';

	protected function generateID()
	{
		$category = DB::table('category')
						->select('CategoryID')
						->distinct()
						->orderBy('CategoryID','desc')
						->first();

		if (count($category) <= 0)
		{
			$newID = "CT000";
		}
		else
		{

			$newLastID = substr($category->CategoryID, 2,3);
			$newLastID = ($newLastID+1);

			if ($newLastID<10)
			{
			    $newLastID = "00".$newLastID;
			}
			else if ($newLastID<100)
			{
			    $newLastID = "0".$newLastID;
			}
			$newID = "CT".$newLastID;
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
