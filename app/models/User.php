<?php
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
class User extends Eloquent implements UserInterface, RemindableInterface {
public $timestamps = false;
protected $fillable = array('Username', 'Password', 'Role', 'Active');
protected $primaryKey = 'Username';

/**
* The database table used by the model.
*
* @var string
*/
protected $table = 'user';
/**
* The attributes excluded from the model's JSON form.
*
* @var array
*/

public function getAuthIdentifier()
{
	return $this->getKey();
}

public function getAuthPassword()
{
	return $this->Password;
}


public function getReminderEmail()
{
	return $this->email;
}

protected $hidden = array('password', 'remember_token');
}