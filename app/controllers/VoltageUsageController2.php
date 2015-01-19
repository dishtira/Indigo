<?php

class WattageUsageController extends IndigoController{

	
	public function insertWattageUsage()
	{
		$wattageUsage = json_decode(Input::get('wattageUsage'));
		$categoryIDs = json_decode(Input::get('categoryIDs'));
		$dateNow = json_decode(Input::get('dateNow'));
		$timeNow = json_decode(Input::get('timeNow'));

		for($i=0; $i<count($categoryIDs);$i++)
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
			$dateShow = (int)$dateNow;

			$dateNow = $dt->format('m');
			$monthShow = (int)$dateNow;

			$dateNow = $dt->format('Y');
			$yearShow = (int)$dateNow;
			
		}
		else
		{
			$dateShow = Input::get('date');
			$monthShow = Input::get('month');
			$yearShow = Input::get('year');
		}
		//return $monthShow." ".$yearShow;

		$wattageUsages = DB::table('wattageusage')
							->selectRaw('sum(Value) as TotalUsage, day(UpdateDate), month(UpdateDate) as Month, year(UpdateDate) as Year, wattageusage.CategoryID, CategoryName')
							->join('category', 'wattageusage.CategoryID', '=', 'category.CategoryID')
							->groupby('Month', 'Year', 'wattageusage.CategoryID', 'CategoryName')
							->orderby('Year', 'desc')
							->orderby('Month', 'asc')
							->orderby('wattageusage.CategoryID', 'asc')
							->whereRaw("month(UpdateDate) = '".$monthShow."' and year(UpdateDate) = '".$yearShow."' ")
							->get();
		//return json_encode($wattageUsages);
		$availableDates = DB::table('wattageusage')
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

		return $this->showPageWith('home', array(
					'wattageUsages' => $wattageUsages,
					'availableDates' => $availableDates,
					'years' => $years,
					'monthShow' => $monthShow,
					'yearShow' => $yearShow
				));
	}
}