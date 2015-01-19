<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', array(
	'as' => 'home',
	'uses' => 'HomeController@showHome'
));

Route::get('register',array(
	'as' => 'register-show',
	'uses' => 'UserController@showRegister'
));

Route::post('schedules/getScheduleData', function()
{
	$currTime = json_decode(Input::get('currTime'));
	$schedules = Schedule::join('Appliance', 'Schedule.ApplianceID', '=', 'Appliance.ApplianceID')
					->where('Time', '=', $currTime)
					->where('Status', '=', '1')
					->select('Appliance.ApplianceID', 'ApplianceName', 'ToState','PinLocation')
					->get();
	$result = null;
	$i = 0;
	$file = "logs/logSchedule.txt";
	date_default_timezone_set("Asia/Bangkok");
	$dt = new DateTime();
	$dateNow = $dt->format('Y-m-j');
	foreach($schedules as $schedule)
	{	
		$result[$i][0] = $schedule->PinLocation;
		$result[$i][1] = (($schedule->ToState == "1") ? "0" : "1");
		$content = $dateNow." ".$currTime." ".$schedule->applianceID." ".$schedule->ApplianceName." ".(($schedule->ToState == "1") ? "On" : "Off")."\n";
		if(File::exists($file))
		{
			$bytes_written = File::append($file, $content);
		}
		else
		{
			$bytes_written = File::put($file, $content);
		}
		$i++;
	}

	return json_encode($result);
});

Route::post('appliances/changeState', array(
	'as' => 'changeState',
	'uses' => 'ApplianceController@changeState'
));

Route::group(array('before' => 'auth'), function()
{

	Route::get('logout', array(
		'as' => 'logout',
		'uses' => 'UserController@doLogout'
	));

	Route::get('users/changePassword', array(
		'as' => 'changePassword-show',
		'uses' => 'UserController@showChangePassword'
	));

	Route::get('rooms/{roomID}',array(
		'as' => 'rooms',
		'uses' => 'ApplianceController@showAppliances'
	));

	Route::get('users/editUser/{username}', array(
		'as' => 'editUser-show',
		'uses' => 'UserController@showEditUser'
	));

	Route::get('report', array(
		'as' => 'report',
		'uses' => 'WattageUsageController@showReport'
	));

	Route::post('report', array(
		'as' => 'report',
		'uses' => 'WattageUsageController@showReport'
	));

	// Route::post('appliances/getState',array(
	// 	'as' => 'getState',
	// 	'uses' => 'ApplianceController@getState'
	// ));


	Route::post('appliances/getState', function()
	{
		$pinLocations = json_decode(Input::get('pinLocations'));

		for ($i=0;$i<count($pinLocations);$i++)
		{
			$state[$i] = exec ("gpio read ".$pinLocations[$i]);
		}

		return json_encode($state);
	});

	// Route::post('appliances/getCurrentValue',array(
	// 	'as' => 'getCurrentValue',
	// 	'uses' => 'ApplianceController@getFileCurrentValue'
	// ));

	Route::post('appliances/getCurrentValue', function(){
		// if (Request::ajax())
		// {
		// 	$file = "tmp/currentValue.txt";
		// 	if (File::exists($file))
		// 	{
		// 	    // $content = File::get($file);
		// 	    // $res = json_decode($content);
		// 	    // for ($i=0;$i<count($res);$i++)
		// 	    // {
		// 	    // 	if(exec ("gpio read ".$i) == "0" )
		// 	    // 	{
		// 	    // 		$res[$i] = 0;
		// 	    // 	}
		// 	    // }
		// 	    $res = "qweasd";
		// 	    return json_encode($res);
		// 	}
		// 	//return json_encode("ajax");
		// }
		// else 
		// {
		// 	return json_encode("error non ajax");
		// }
		return json_encode("asdqweasd");
	});

	// Route::post('appliances/getSerialValue',array(
	// 	'as' => 'getSerialValue',
	// 	'uses' => 'ApplianceController@getCurrentSerialValue'
	// ));

	Route::post('appliances/getSerialValue',function(){

		if(Request::ajax())
		{
			//$pinLocations = json_decode(Input::get('pinLocations'));

			exec("sudo python serialread.py 5");
			//$currVal = "testing";//exec ("sudo python serialread.py 5");
			//$currVal = "[\"2.07\", \"2.23\", \"2.10\", \"1.06\"]";
			// for ($i=0;$i<count($pinLocations);$i++)
			// {
			// 	$state[$pinLocations[$i]] = exec ("gpio read ".$pinLocations[$i]);
			//     if ($state[$pinLocations[$i]] == "1" )
			//     {
		 //    		$currVal[$pinLocations[$i]] = exec ("sudo python serialread.py ".($pinLocations[$i]+1));
			//     }
			//     else
			//     {
			//         $currVal[$pinLocations[$i]] = "0";
			//     }
			//     //usleep(10000);
			// }

			//$file = "tmp/currentValue.txt";

			//$bytes_written = File::put($file, $currVal);
			//$currVal = "testing";
			return json_encode("testing");
		}
		else
		{
			return json_encode("non ajax");
		}

	});

	Route::get('appliances/writeFile', array(
		'as' => 'writeFile',
		'uses' => 'ApplianceController@writeFile'
	));

	Route::get('appliances/readFile', array(
		'as' => 'readFile',
		'uses' => 'ApplianceController@readFile'
	));

	Route::get('appliances/addAppliance/{roomID}', array(
		'as' => 'addAppliance-show',
		'uses' => 'ApplianceController@showAddAppliance'
	));

	// Route::post('appliances/addAppliance', array(
	// 	'as' => 'addAppliance-show',
	// 	'uses' => 'ApplianceController@showAddAppliance'
	// ));

	Route::post('appliances/addAppliance', array(
		'as' => 'addAppliance-post',
		'uses' => 'ApplianceController@addAppliance'
	));

	Route::get('appliances/deleteAppliance/{applianceID}', array(
		'as' => 'deleteAppliance',
		'uses' => 'ApplianceController@deleteAppliance'
	));

	Route::get('appliances/editAppliance/{applianceID}', array(
		'as' => 'editAppliance-show',
		'uses' => 'ApplianceController@showEditAppliance'
	));

	Route::post('appliances/editAppliance', array(
		'as' => 'editAppliance-post',
		'uses' => 'ApplianceController@editAppliance'
	));

	Route::get('adminPanel', array(
		'as' => 'adminPanel-show',
		'uses' => 'AdminController@showAdminPanel'
	));

	Route::get('rooms/manage/addRoom', array(
		'as' => 'addRoom-show',
		'uses' => 'RoomController@showAddRoom'
	));

	Route::post('rooms/manage/addRoom', array(
		'as' => 'addRoom-post',
		'uses' => 'RoomController@addRoom'
	));

	Route::get('rooms/manage/editRoom/{roomID}', array(
		'as' => 'editRoom-show',
		'uses' => 'RoomController@showEditRoom'
	));

	Route::post('rooms/manage/editRoom', array(
		'as' => 'editRoom-post',
		'uses' => 'RoomController@editRoom'
	));

	Route::get('rooms/manage/deleteRoom/{roomID}', array(
		'as' => 'deleteRoom',
		'uses' => 'RoomController@deleteRoom'
	));

	Route::get('categories/addCategory', array(
		'as' => 'addCategory-show',
		'uses' => 'CategoryController@showAddCategory'
	));

	Route::post('categories/addCategory', array(
		'as' => 'addCategory-post',
		'uses' => 'CategoryController@addCategory'
	));

	Route::get('categories/editCategory/{categoryID?}', array(
		'as' => 'editCategory-show',
		'uses' => 'CategoryController@showEditCategory'
	));

	Route::post('categories/editCategory', array(
		'as' => 'editCategory-post',
		'uses' => 'CategoryController@editCategory'
	));

	Route::get('categories/deleteCategory/{categoryID?}', array(
		'as' => 'deleteCategory',
		'uses' => 'CategoryController@deleteCategory'
	));

	Route::get('users/{username}/{action}', array(
		'as' => 'userActivation',
		'uses' => 'UserController@activateUser'
	));

	Route::get('schedule', array(
		'as' => 'scheduling',
		'uses' => 'ScheduleController@showSchedules'
	));

	Route::get('schedule/addSchedule', array(
		'as' => 'addSchedule-show',
		'uses' => 'ScheduleController@showAddSchedule'
	));

	Route::get('schedule/editSchedule/{scheduleID}', array(
		'as' => 'editSchedule-show',
		'uses' => 'ScheduleController@showEditSchedule'
	));

	Route::get('schedule/activateSchedule/{scheduleID}/{action}', array(
		'as' => 'scheduleActivation',
		'uses' => 'ScheduleController@activateSchedule'
	));

	Route::get('schedule/deleteSchedule/{scheduleID}', array(
		'as' => 'deleteSchedule',
		'uses' => 'ScheduleController@deleteSchedule'
	));

	Route::group(array('before' => 'csrf'), function()
	{
		Route::post('users/editUser', array(
			'as' => 'editUser-post',
			'uses' => 'UserController@editUser'
		));

		Route::post('schedule/addSchedule', array(
			'as' => 'addSchedule-post',
			'uses' => 'ScheduleController@addSchedule'
		));

		Route::post('schedule/editSchedule', array(
			'as' => 'editSchedule-post',
			'uses' => 'ScheduleController@editSchedule'
		));

	});

});

Route::group(array('before' => 'guest'), function(){

	Route::group(array('before' => 'csrf'), function()
	{

		Route::post('login', array(
			'as' => 'doLogin',
			'uses' => 'UserController@doLogin'
		));

		Route::post('register', array(
			'as' => 'register-post',
			'uses' => 'UserController@register'
		));

	});

});

Route::get('showBackgroundProcess', array(
	'as' => 'showBackgroundProcess',
	'uses' => 'ApplianceController@showBackgroundProcess'
));

Route::get('testing', function(){
	date_default_timezone_set("Asia/Bangkok");
	$dt = new DateTime();
	$dateNow = $dt->format('l, j F Y');
	echo date('l, j F Y');
});

Route::post('admin/insertWattageUsage', array(
	'as' => 'insertWattageUsage',
	'uses' => 'WattageUsageController@insertWattageUsage'
));