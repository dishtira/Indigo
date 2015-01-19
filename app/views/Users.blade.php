<div class="table-responsive">
    @if($users == null || $users->count() <=0 )
    <div class="form-group">
        <label>There is no user yet</label>
    </div>
    @else
    <table class="table table-striped table-bordered table-hover" id="dataTablesRooms">
        <thead>
            <tr>  
                <th>Username</th>
                <th>Role</th>
                <th>Activation Status</th>
                <th>Activation Action</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach ($users as $user)
            {
        ?>
            <tr class="odd gradeX">
                <td> <?= $user->Username ?></td>
                <td> <?= $user->Role ?> </td>
                <td>
                    <?php
                        if ($user->Active == "1")
                        {
                    ?>
                        <font color="green">
                            Activated
                        </font>
                    <?php
                        }
                        else
                        {
                    ?>      
                        <font color="red">
                            Deactivated
                        </font>
                    <?php
                        }
                    ?>
                </td>
                <!-- <td> <?= ($user->Active == "1") ? "<a href=\"URL::to('users/'.$user->Username.'/deactivate')\"><button class=\"btn btn-danger\"><i class=\"glyphicon glyphicon-remove\"></i> Deactivate</button></a>" : "<a href=\"{{ URL::to('users/'.$user->Username.'/activate') }}\"><button class=\"btn btn-success\"><i class=\"glyphicon glyphicon-ok\"></i> Activate</button></a>" ?></td> -->
                <td>
                    <?php
                        if ($user->Active == "1")
                        {
                    ?>
                            <a href="{{URL::to('users/'.$user->Username.'/deactivate')}}"><button class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i> Deactivate</button></a>
                    <?php
                        }
                        else
                        {
                    ?>      <a href="{{ URL::to('users/'.$user->Username.'/activate') }}"><button class="btn btn-success"><i class="glyphicon glyphicon-ok"></i> Activate</button></a>

                    <?php
                        }
                    ?>
                </td>
                <td>
                    <a href=" {{ URL::to('users/editUser/'.$user->Username) }} "><button class="btn btn-primary"><i class="fa fa-edit"></i> Edit</button></a>
                    <a href=""> <button class="btn btn-danger"><i class="fa fa-eraser"></i> Delete</button></a>
                </td>
            </tr>
        <?php

            }
        ?>                                           
        </tbody>
    </table>
    @endif
</div>