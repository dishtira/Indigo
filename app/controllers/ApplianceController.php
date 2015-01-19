<?php
class ApplianceController extends IndigoController{

	public function showAppliances($roomID = null)
	{
		$appliances = Appliance::where('RoomID','=',$roomID)->get();
		$room = Room::where('RoomID','=',$roomID);

		return $this->showPageWith('appliance', array(
			'appliances'	=> $appliances,
			'room'			=> $room->first()
		));
	}

	public function showBackgroundProcess()
	{
		return $this->showPage('backgroundProcess');
	}

	public function getState()
	{		
		$pinLocations = json_decode(Input::get('pinLocations'));

		for ($i=0;$i<count($pinLocations);$i++)
		{
			$state[$i] = exec ("gpio read ".$pinLocations[$i]);
		}

		return json_encode($state);
	}

	public function getCurrentSerialValue()
	{
		$pinLocations = json_decode(Input::get('pinLocations'));
		for ($i=0;$i<count($pinLocations);$i++)
		{
			$state[$i] = exec ("gpio read ".$pinLocations[$i]);
		    if ($state[$i] == "1" )
		    {
		        $currVal[$i] = exec ("sudo python serialread.py ".($pinLocations[$i]+1));
		    }
		    else
		    {
		        $currVal[$i] = "0";
		    }
		}

		$file = "tmp/currentValue.txt";

		if (File::exists($file))
		{
			$bytes_written = File::put($file, json_encode($currVal));
		}

		return json_encode($currVal);
	}

	public function getFileCurrentValue()
	{
		$file = "tmp/currentValue.txt";
		if (File::exists($file))
		{
		    $content = File::get($file);
		    return $content;
		}
		//return json_encode("asd");
	}

	public function changeState()
	{
		$pin = Input::get('pin');
		$res = "";
		if (isset($pin))
		{
			$pin = strip_tags($pin);
			$status = Input::get('from_status');

			if(isset($status) && $status != null && $status != "")
			{
				$status = strip_tags($status);
			}
			else
			{
				$status = exec ("gpio read ".$pin);
			}

			if ( (is_numeric($pin)) && (is_numeric($status)) && ($pin <= 7) && ($pin >= 0) && ($status == 0) || ($status == 1) ) 
			{
				//set the gpio's mode to output		
				system("gpio mode ".$pin." out");
				//set the gpio to high/low
				if ($status == 0 ) { $status = 1; }
				else if ($status == 1 ) { $status = 0; }
				system("gpio write ".$pin." ".$status );
				//reading pin's status
				exec ("gpio read ".$pin, $status, $return );
				//printing it
				//echo ( $status[0] );
				//echo "success";
				$result = "success";
			}
			else
			{
				$result = "failed";
			}
		}
		else
		{
			$result = "not set";
		}
		return $result;
	}

	public function writeFile($file,$content)
	{
		if (File::exists($file))
		{
			$bytes_written = File::put($file, json_encode($content));
			if ($bytes_written === false)
			{
			   	//return "write file error";
			}
		}
		//return "write file success";
	}

	public function readFile()
	{
		$file = "data.txt";
		if (File::exists($file))
		{
		    $content = File::get($file);
		    return $content;
		}
		else 
		{
			return "file not exist";
		}
	}

	public function showAddAppliance($roomID = null)
	{

		$room = Room::where('RoomID','=',$roomID);
		$categories = Category::where('Status','=','1')->get();
		$newApplianceID = Appliance::generateID();

		return $this->showPageWith('addAppliance', array(
			'room'		=> $room->first(),
			'newID' 	=> $newApplianceID,
			'categories' 	=> $categories
		));
	}

	public function addAppliance()
	{
		$validator = Validator::make(Input::all(), array(
			'applianceID' 	=> 'required|unique:appliance',
			'categoryID'	=> 'required',
			'pinLocation'	=> 'required|numeric',
			'applianceName'	=> 'required',
			'onPower'		=> 'required|numeric',
			'standbyPower'	=> 'required|numeric'
		));


		$roomID = Input::get('roomID');
		if ($validator->fails())
		{
			return Redirect::to("appliances/addAppliance/".$roomID)
					->withErrors($validator)
					->withInput();
		}
		else
		{
			$applianceID = Input::get('applianceID');
			$categoryID = Input::get('categoryID');
			$pinLocation = Input::get('pinLocation');
			$applianceName = Input::get('applianceName');
			$onPower = Input::get('onPower');
			$standbyPower = Input::get('standbyPower');

			$create = Appliance::create(array(
				'ApplianceID' 	=> $applianceID,
				'CategoryID'	=> $categoryID,
				'ApplianceName' => $applianceName,
				'OnPower' 		=> $onPower,
				'StandbyPower' 	=> $standbyPower,
				'OffPower' 		=> 0,
				'PinLocation' 	=> $pinLocation,
				'RoomID'		=> $roomID
			));

			if($create)
			{
				$this->updateIDBackground();
				return Redirect::to("rooms/".$roomID);
			}
			else
			{
				return "insert new appliace failed";
			}
		}
	}

	public function deleteAppliance($applianceID = null)
	{
		$appliance = Appliance::where('ApplianceID','=',$applianceID);
		$roomID = $appliance->first()->RoomID;
		$appliance->delete();
		$this->updateIDBackground();
		return Redirect::to("rooms/".$roomID);
	}

	public function showEditAppliance($applianceID = null)
	{
		$appliance = Appliance::where('ApplianceID','=',$applianceID);
		$categories = Category::where('Status','=','1')->get();
		$room = Room::where('RoomID','=',$appliance->first()->RoomID);
		return $this->showPageWith('editAppliance', array(
			'room' => $room->first(),
			'appliance' => $appliance->first(),
			'categories' => $categories
		));
	}

	public function editAppliance()
	{
		$validator = Validator::make(Input::all(), array(
			'applianceID' 	=> 'required',
			'categoryID'	=> 'required',
			'pinLocation'	=> 'required|numeric',
			'applianceName'	=> 'required',
			'onPower'		=> 'required|numeric',
			'standbyPower'	=> 'required|numeric'
		));


		$applianceID = Input::get('applianceID');
		if ($validator->fails())
		{
			return Redirect::to("appliances/editAppliance/".$applianceID)
					->withErrors($validator)
					->withInput();
		}
		else
		{
			$roomID = Input::get('roomID');
			$categoryID = Input::get('categoryID');
			$pinLocation = Input::get('pinLocation');
			$applianceName = Input::get('applianceName');
			$onPower = Input::get('onPower');
			$standbyPower = Input::get('standbyPower');

			$appliance = Appliance::where('ApplianceID','=',$applianceID)->first();
			$appliance->CategoryID = $categoryID;
			$appliance->PinLocation = $pinLocation;
			$appliance->ApplianceName = $applianceName;
			$appliance->OnPower = $onPower;
			$appliance->StandbyPower = $standbyPower;

			$appliance->save();

			$this->updateIDBackground();

			//return print_r($appliance);
			return Redirect::to("rooms/".$roomID);
		}
	}

	public function updateIDBackground()
	{
		$applianceRes = DB::table('appliance')
					->join('category', 'appliance.CategoryID', '=', 'category.CategoryID')
					->select('appliance.CategoryID','category.CategoryName', 'appliance.PinLocation')
					->orderby('appliance.PinLocation','asc')
					->get();
		$categoryIDs = null;
		for( $i=0;$i<count($applianceRes);$i++)
		{
			$categoryIDs[$i] = $applianceRes[$i]->CategoryID;
			$categoryNames[$i] = $applianceRes[$i]->CategoryName;
			$pinLocations[$i] = $applianceRes[$i]->PinLocation;
		}


		date_default_timezone_set("Asia/Bangkok");
		$dt = new DateTime();
		$dateNow = $dt->format('Y-m-j');
		//$dateNow = $dt->format('l, j F Y');
		$lastUpdate = "";
		$categoryRes = DB::table('category')
						->select('CategoryID')
						->orderby('CategoryID','asc')
						->get();
		$posCategoryID = null;
		for( $i=0;$i<count($categoryRes);$i++ )
		{
			$posCategoryID[$i] = $categoryRes[$i]->CategoryID;

			$volRes = WattageUsage::where('UpdateDate', '=', $dateNow)
						->where('CategoryID', '=', $posCategoryID[$i])
						->get();
			if(count($volRes) <= 0)
			{
				$volValue[$i] = 0;
			}
			else
			{
				$volValue[$i] = $volRes[0]->Value;
				$lastUpdate = $volRes[0]->UpdateTime;
			}

		}

		if($categoryIDs == null)
		{
			$categoryIDs = "";
			$pinLocations = "";
		}

		if($posCategoryID == null)
		{
			$posCategoryID = "";
			$volValue = "";
		}

		$categoryIDsF = json_encode($categoryIDs);
		$file = "tmp/categoryIDs.txt";
		$bytes_written = File::put($file, $categoryIDsF);

		$posCategoryIDF = json_encode($posCategoryID);
		$file = "tmp/posCategoryID.txt";
		$bytes_written = File::put($file, $posCategoryIDF);

		$pinLocationsF = json_encode($pinLocations);
		$file = "tmp/pinLocations.txt";
		$bytes_written = File::put($file, $pinLocationsF);
	}
}