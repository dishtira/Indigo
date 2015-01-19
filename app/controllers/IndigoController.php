<?php

class IndigoController extends BaseController{

	protected function showPage($page)
	{
		$_roomsList = Room::all();
		return View::make($page)->with('_roomsList',$_roomsList);
	}

	protected function showPageWith($page,$varData)
	{
		$_roomsList = Room::all();
		return View::make($page, $varData)->with('_roomsList',$_roomsList);
	}
}