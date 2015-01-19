<?php

class AdminController extends IndigoController{
	
	public function showAdminPanel()
	{
		$rooms = Room::all();
		$categories = Category::where('Status','=','1')->get();
		$users = User::where('username', 'not like', 'admin')->get();
		//$wattageUsages = DB::select(DB::raw('select CategoryID, month(UpdateDate) as month from wattageusage group by month(UpdateDate)'));
		$wattageUsages = DB::table('wattageusage')
							->selectRaw('sum(Value) as TotalUsage, month(UpdateDate) as Month, year(UpdateDate) as Year, CategoryID')
							->groupby('Month', 'Year', 'CategoryID')
							->orderby('Year', 'desc')
							->orderby('Month', 'desc')
							->orderby('CategoryID', 'asc')
							->get();

		/*select sum(value) as 'WattageUsage' , month(UpdateDate) as 'Month', year(UpdateDate) as 'Year', CategoryID 
		from wattageusage 
		group by month(UpdateDate), year(UpdateDate), categoryID 
		order by year(UpdateDate) desc, month(UpdateDate) desc*/
		return $this->showPageWith('adminPanel', array(
			'rooms' => $rooms,
			'categories' => $categories,
			'users' => $users,
			'wattageUsages' => $wattageUsages
		));
	}

}