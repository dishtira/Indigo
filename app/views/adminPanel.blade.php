@extends('layout.BaseTemplate')

@section('title')
	Admin Panel
@stop

@section('content')
	<div class="panel panel-default">
        <div class="panel-heading headerFont">
            Dashboard
        </div>
        <div class="panel-body">
            <div class="panel-group" id="accordion">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" id="manageRooms" data-parent="#accordion" href="#manageRoomsTarget" class="collapsed">Manage Rooms</a>
                        </h4>
                    </div>
                    <div id="manageRoomsTarget" class="panel-collapse in" style="height: auto;">
                        <div class="panel-body">
                            @include('Rooms')
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" id="manageCategories" data-parent="#accordion" href="#manageCategoriesTarget" class="collapsed">Manage Categories</a>
                        </h4>
                    </div>
                    <div id="manageCategoriesTarget" class="panel-collapse collapse" style="height: auto;">
                        <div class="panel-body">
                            @include('Categories')
                        </div>
                    </div>
                </div>
                @if( Auth::user()->Role == "admin" )
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" id="manageUsers" data-parent="#accordion" href="#manageUsersTarget" class="collapsed">Manage Users</a>
                        </h4>
                    </div>
                    <div id="manageUsersTarget" class="panel-collapse collapse" style="height: auto;">
                        <div class="panel-body">
                            @include('Users')
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
	</div>
@stop

@section('javascript')
<script type="text/javascript">
$(document).ready(function () {
    $('#dataTablesRooms').dataTable();
    $('#dataTablesCategories').dataTable();
});
</script>
@stop