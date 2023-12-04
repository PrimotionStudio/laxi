
    <div class="sidebar">
      <div class="sidebar-header">
        <div>
          <a href="#" class="sidebar-logo"><span>Laxi</span></a>
        </div>
      </div><!-- sidebar-header -->
      <div id="dpSidebarBody" class="sidebar-body">
        <?php
        $dir = explode("/", $_SERVER["PHP_SELF"]);
        $page = explode(".", end($dir));
        ?>
        <ul class="nav nav-sidebar">
          <li class="nav-item">
            <a href="index" class="nav-link<?php if ($page[0] == "index"){echo " active";}?>"><i data-feather="box"></i> Dashboard</a>
          </li>
          <li class="nav-item">
            <a href="schedule" class="nav-link<?php if ($page[0] == "schedule"){echo " active";}?>"><i data-feather="watch"></i> Schedule</a>
          </li>
          <li class="nav-item">
            <a href="calendar" class="nav-link<?php if ($page[0] == "calendar"){echo " active";}?>"><i data-feather="calendar"></i> Calendar</a>
          </li>
        </ul>

        <hr class="mg-t-30 mg-b-25">

        <ul class="nav nav-sidebar">
          <li class="nav-item"><a href="logout" class="nav-link text-danger"><i data-feather="log-out"></i> Logout</a></li>
        </ul>


      </div><!-- sidebar-body -->
    </div><!-- sidebar -->
