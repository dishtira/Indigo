@extends('layout.BaseTemplate')

@section('title')
	Edit Category
@stop


@section('content')
<div class="panel panel-default">
	<div class="panel-heading headerFont">
		Category
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6">
				<form action=" {{ URL::route('editCategory-post') }} " method="post">
					<div class="form-group @if ($errors->has('categoryID')) has-error @endif">
						<label>Category ID</label>
						<input type="hidden" name="categoryID" value="{{ $category->CategoryID }}"/>
						<input class="form-control" placeholder="Category ID" value="{{ $category->CategoryID }}" disabled="" />
						@if ($errors->has('categoryID'))<label class="control-label" for="inputError">Category ID must be filled</label> @endif
					</div>
					<div class="form-group @if ($errors->has('categoryName')) has-error @endif">
						<label>Category Name</label>
						<input class="form-control" name="categoryName" placeholder="Category Name" value="{{ (Input::old('categoryName')) ? e(Input::old('categoryName')) : $category->CategoryName }}" />
						@if ($errors->has('categoryName'))<label class="control-label" for="inputError">Category Name must be filled</label> @endif
					</div>
					<div class="form-group">
						<input type="submit" class="btn btn-success" value="Edit Category"></input>
		                <input type="reset" class="btn btn-md btn-danger"></input>
		            </div>
				</form>
			</div>
		</div>
	</div>
</div>
@stop