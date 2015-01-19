<?php
class CategoryController extends IndigoController{

	public function showAddCategory()
	{
		$newID = Category::generateID();
		return $this->showPageWith('addCategory', array(
			'newID' => $newID
		));
	}

	public function addCategory()
	{
		$validator = Validator::make(Input::all(), array(
			'categoryID'	=> 'required|unique:category',
			'categoryName'	=> 'required'
		));

		if($validator->fails())
		{
			return Redirect::route('addCategory-show')
					->withErrors($validator)
					->withInput();
		}
		else
		{
			$categoryID = Input::get('categoryID');
			$categoryName = Input::get('categoryName');

			$create = Category::create(array(
				'CategoryID'	=> $categoryID,
				'CategoryName'	=> $categoryName
			));

			if ($create)
			{
				$this->updateCategoryBackground();
				return Redirect::route('adminPanel-show');
			}
			else
			{
				return "Failed Add Category";
			}

		}
	}

	public function showEditCategory($categoryID = null)
	{
		$category = Category::where('CategoryID','=',$categoryID)->first();

		return $this->showPageWith('editCategory', array(
			'category' => $category
		));
	}

	public function editCategory()
	{
		$validator = Validator::make(Input::all(), array(
			'categoryName'	=> 'required'
		));

		$categoryID = Input::get('categoryID');
		if($validator->fails())
		{
			return Redirect::to('categories/editCategory/'.$categoryID)
					->withErrors($validator)
					->withInput();
		}
		else
		{
			$categoryName = Input::get('categoryName');

			$category = Category::where('CategoryID', '=', $categoryID)->first();
			$category->CategoryName = $categoryName;
			$category->save();
			$this->updateCategoryBackground();
			return Redirect::route('adminPanel-show');	
		}
	}

	public function deleteCategory($categoryID = null)
	{
		// $category = Category::where('CategoryID', '=', $categoryID)->delete();
		$category = Category::where('CategoryID', '=', $categoryID)->first();
		$category->Status = 0;
		$category->save();
		$this->updateCategoryBackground();
		return Redirect::route('adminPanel-show');
	}

	public function updateCategoryBackground()
	{

		date_default_timezone_set("Asia/Bangkok");
		$dt = new DateTime();
		$dateNow = $dt->format('l, j F Y');

		$categoryRes = DB::table('category')
						->select('CategoryID')
						->orderby('CategoryID','asc')
						->get();
		$posCategoryID = [];
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
			}

		}

		if($posCategoryID == null || count($posCategoryID) <=0 )
		{
			$posCategoryID = "";
		}

		$posCategoryIDF = json_encode($posCategoryID);
		$file = "tmp/posCategoryID.txt";
		$bytes_written = File::put($file, $posCategoryIDF);

	}
}