<div class="table-responsive">
    @if(count($categories) <= 0)
    <div class="form-group">
        <label>There is no category yet</label>
    </div>
    @else
    <table class="table table-striped table-bordered table-hover" id="dataTablesCategories">
        <thead>
            <tr>  
                <th>Category ID</th>
                <th>Category Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach($categories as $category)
            {
        ?>
            <tr class="odd gradeX">
                <td>{{$category->CategoryID}}</td>
                <td>{{$category->CategoryName}}</td>
                <td>
                    <a href="{{ URL::to('categories/editCategory/'.$category->CategoryID) }}"><button class="btn btn-primary"><i class="fa fa-edit"></i> Edit</button></a>
                    <a href="{{ URL::to('categories/deleteCategory/'.$category->CategoryID) }}"> <button class="btn btn-danger"><i class="fa fa-eraser"></i> Delete</button></a>
                </td>
            </tr>
        <?php

            }
        ?>                                           
        </tbody>
    </table>
    @endif
    <a href="{{ URL::route('addCategory-show') }}"><button type="submit" class="btn btn-success"><i class="fa fa-plus-square"></i> Add Category</button></a>
</div>