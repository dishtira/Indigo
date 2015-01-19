<div class="table-responsive">
    @if($rooms == null || $rooms->count() <=0 )
    <div class="form-group">
        <label>There is no room yet</label>
    </div>
    @else
    <table class="table table-striped table-bordered table-hover" id="dataTablesRooms">
        <thead>
            <tr>  
                <th>Room ID</th>
                <th>Room Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach ($rooms as $room)
            {
        ?>
            <tr class="odd gradeX">
                <td> <?= $room->RoomID ?></td>
                <td> <?= $room->RoomName ?> </td>
                <td>
                    <a href="{{ URL::to('rooms/manage/editRoom/'.$room->RoomID ) }}"><button class="btn btn-primary"><i class="fa fa-edit"></i> Edit</button></a>
                    <a href="{{ URL::to('rooms/manage/deleteRoom/'.$room->RoomID) }}"> <button class="btn btn-danger"><i class="fa fa-eraser"></i> Delete</button></a>
                </td>
            </tr>
        <?php

            }
        ?>                                           
        </tbody>
    </table>
    @endif
    <a href="{{ URL::route('addRoom-show') }}"><button type="submit" class="btn btn-success"><i class="fa fa-plus-square"></i> Add Room</button></a>
</div>