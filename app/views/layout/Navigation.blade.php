<?php
   $url = Request::url();
?>

<nav class="navbar-default navbar-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav" id="main-menu">
		<li class="text-center">
            {{ HTML::image('assets/img/indigo.png', '' , array('width' => '250px', 'class' => 'user-image img-responsive') ); }}
			</li>
            @if (Auth::check())
            <li>
                <a  href="{{ URL::route('changePassword-show') }}" class="{{ (strpos( $url, 'users/changePassword' ) !== false) ?  e('active-menu') : '' }}" ><i class="glyphicon glyphicon-cog fa-2x"></i> Change Password</a>
            </li>   
                @if(Auth::user()->Role != "guest")
                <li>
                    <a  href="{{ URL::route('adminPanel-show') }}" class="{{ (strpos( $url, 'adminPanel' ) !== false) || (strpos( $url, '/rooms/manage' ) !== false) ?  e('active-menu') : '' }}" ><i class="fa fa-edit fa-2x"></i> Admin Panel</a>
                </li>
                @endif  
            <li>
                <a href="#" class="{{ (strpos( $url, '/RM' ) !== false) ?  e('active-menu') : '' }}"><i class="fa fa-sitemap fa-2x"></i> Rooms<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <?php
                        foreach($_roomsList as $_room)
                        {
                            //echo "asd";//;
                    ?>
                        <li>
                            <a href=" {{ URL::to('rooms', $_room->RoomID ) }} " class="{{ (strpos( $url, $_room->RoomID ) !== false) ?  e('active-menu') : '' }}" >{{ $_room->RoomName }}</a>
                        </li>
                    <?php
                        }
                    ?>                    
                </ul>
            </li>
                @if(Auth::user()->Role != "guest")
                <li>
                    <a  href="{{ URL::route('scheduling') }}" class="{{ (strpos( $url, 'schedule' ) !== false) ?  e('active-menu') : '' }}" ><i class="glyphicon glyphicon-time fa-2x"></i> Schedule</a>
                </li>
                <li>
                    <a  href="{{ URL::route('report') }}" class="{{ (strpos( $url, 'report' ) !== false) ?  e('active-menu') : '' }}" ><i class="glyphicon glyphicon-list-alt fa-2x"></i> Report</a>
                </li>
                @endif
            @else
            <li>
                <a  href="{{ URL::route('register-show') }}" class="{{ (strpos( $url, 'register' ) !== false) ?  e('active-menu') : '' }}" ><i class="glyphicon glyphicon-user fa-2x"></i> Register</a>
            </li> 
            @endif
        </ul>
       
    </div>
    
</nav>