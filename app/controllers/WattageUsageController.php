<?php

class WattageUsageController extends IndigoController{

	
	public function insertWattageUsage()
	{
		$wattageUsage = json_decode(Input::get('wattageUsage'));
		$categoryIDs = json_decode(Input::get('categoryIDs'));
		$dateNow = json_decode(Input::get('dateNow'));
		$timeNow = json_decode(Input::get('timeNow'));
		$result = [];
		$wattageUsage[];
		for($i=0; $i<count($wattageUsage);$i++)
		{
			if($wattageUsage[$i] != null && $wattageUsage[$i] != "" && $categoryIDs[$i] != "" )
			{
				$volRes = WattageUsage::where('UpdateDate', '=', $dateNow)
							->where('CategoryID', '=', $categoryIDs[$i])
							->get();
				if (count($volRes) <= 0)
				{
					$create = WattageUsage::create(array(
						'CategoryID' 	=> $categoryIDs[$i],
						'Value'			=> $wattageUsage[$i],
						'UpdateDate'	=> $dateNow,
						'UpdateTime'	=> $timeNow
					));
					$result[$i] = "insert ".$categoryIDs[$i];
				}
				else
				{
					$volGet = WattageUsage::where('CategoryID', '=', $categoryIDs[$i])
								->where('UpdateDate', '=', $dateNow)
								->first();
					$volGet->Value = $wattageUsage[$i];
					$volGet->UpdateTime = $timeNow;
					$volGet->save();
					$result[$i] = "update ".$categoryIDs[$i];
				}
			}
		}

		return json_encode($result);		
	}

	public function showReport()
	{
		// $yearShow = "year";
		// $monthShow = "month";
		if(Input::get('day') == null && Input::get('month') == null && Input::get('year') == null)
		{
			date_default_timezone_set("Asia/Bangkok");
			$dt = new DateTime();
			$dateNow = $dt->format('d');
			$dayShow = (int)$dateNow;

			$dateNow = $dt->format('m');
			$monthShow = (int)$dateNow;

			$dateNow = $dt->format('Y');
			$yearShow = (int)$dateNow;
			
		}
		else
		{

			if (Input::get('day') != null)
			{
				$dayShow = Input::get('day');
				$monthShow = Input::get('month');
			}
			else
			{

				if(Input::get('month') == null)
				{
					$tempDB = DB::table('wattageusage')
								->selectRaw('day(UpdateDate) as Day, month(UpdateDate) as Month')
								->groupby('Day', 'Month')
								->orderby('Month', 'asc')
								->orderby('Day', 'asc')
								->whereRaw("year(UpdateDate) = '".Input::get('year')."'")
								->first();
					$dayShow = $tempDB->Day;
					$monthShow = $tempDB->Month;
				}
				else if (Input::get('month') != null)
				{
					$monthShow = Input::get('month');
					$tempDB = DB::table('wattageusage')
								->selectRaw('day(UpdateDate) as Day')
								->groupby('Day')
								->orderby('Day', 'asc')
								->whereRaw("month(UpdateDate) = '".$monthShow."' and year(UpdateDate) = '".Input::get('year')."'")
								->first();
					$dayShow = $tempDB->Day;
				}

				
			}
			
			if (Input::get('year') != null)
			{
				$yearShow = Input::get('year');
			}			
		}

		$dayUsages = DB::table('wattageusage')
						->selectRaw('sum(Value) as TotalUsage, day(UpdateDate) as Day, month(UpdateDate) as Month, year(UpdateDate) as Year, wattageusage.CategoryID, CategoryName')
						->join('category', 'wattageusage.CategoryID', '=', 'category.CategoryID')
						->groupby('Day', 'Month', 'Year', 'wattageusage.CategoryID', 'CategoryName')
						->orderby('Year', 'desc')
						->orderby('Month', 'asc')
						->orderby('Day', 'asc')
						->orderby('wattageusage.CategoryID', 'asc')
						->whereRaw("day(UpdateDate) = '".$dayShow."' and month(UpdateDate) = '".$monthShow."' and year(UpdateDate) = '".$yearShow."' ")
						->get();
		$monthUsages = DB::table('wattageusage')
						->selectRaw('sum(Value) as TotalUsage, month(UpdateDate) as Month, year(UpdateDate) as Year, wattageusage.CategoryID, CategoryName')
						->join('category', 'wattageusage.CategoryID', '=', 'category.CategoryID')
						->groupby('Month', 'Year', 'wattageusage.CategoryID', 'CategoryName')
						->orderby('Year', 'desc')
						->orderby('Month', 'asc')
						->orderby('wattageusage.CategoryID', 'asc')
						->whereRaw("month(UpdateDate) = '".$monthShow."' and year(UpdateDate) = '".$yearShow."' ")
						->get();

		$yearUsages = DB::table('wattageusage')
						->selectRaw('sum(Value) as TotalUsage, year(UpdateDate) as Year, wattageusage.CategoryID, CategoryName')
						->join('category', 'wattageusage.CategoryID', '=', 'category.CategoryID')
						->groupby('Year', 'wattageusage.CategoryID', 'CategoryName')
						->orderby('Year', 'desc')
						->orderby('wattageusage.CategoryID', 'asc')
						->whereRaw("year(UpdateDate) = '".$yearShow."' ")
						->get();	

		//return $monthShow." ".$yearShow;

		// $wattageUsages = DB::table('wattageusage')
		// 					->selectRaw('sum(Value) as TotalUsage, month(UpdateDate) as Month, year(UpdateDate) as Year, wattageusage.CategoryID, CategoryName')
		// 					->join('category', 'wattageusage.CategoryID', '=', 'category.CategoryID')
		// 					->groupby('Month', 'Year', 'wattageusage.CategoryID', 'CategoryName')
		// 					->orderby('Year', 'desc')
		// 					->orderby('Month', 'asc')
		// 					->orderby('wattageusage.CategoryID', 'asc')
		// 					->whereRaw("month(UpdateDate) = '".$monthShow."' and year(UpdateDate) = '".$yearShow."' ")
		// 					->get();
		//return json_encode($wattageUsages);

		$availableDays = DB::table('wattageusage')
						->selectRaw('day(UpdateDate) as Day, month(UpdateDate) as Month, year(UpdateDate) as Year')
						->groupby('Day', 'Month', 'Year')
						->orderby('Day', 'asc')
						->orderby('Month', 'asc')
						->orderby('Year', 'desc')
						->get();

		$availableMonths = DB::table('wattageusage')
						->selectRaw('month(UpdateDate) as Month, year(UpdateDate) as Year')
						->groupby('Month', 'Year')
						->orderby('Month', 'asc')
						->orderby('Year', 'desc')
						->get();

		$years = DB::table('wattageusage')
					->selectRaw('year(UpdateDate) as Year')
					->groupby('Year')
					->orderby('Year', 'desc')
					->get();

		$file = "logs/KWHPrice.txt";
		$KWHPrice = 0;
		try{
			$KWHPrice = json_decode(File::get($file));
		}
		catch(Exception $e)
		{
		    
		}

		//return $dayShow." ".$monthShow." ".$yearShow;

		return $this->showPageWith('report', array(
					'dayUsages' => $dayUsages,
					'yearUsages' => $yearUsages,
					'monthUsages' => $monthUsages,
					'availableDays' => $availableDays,
					'availableMonths' => $availableMonths,
					'years' => $years,
					'dayShow' => $dayShow,
					'monthShow' => $monthShow,
					'yearShow' => $yearShow,
					'KWHPrice' => $KWHPrice
				));
	}
}