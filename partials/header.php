
      <div class="header sticky-top">
        <div class="header-left">
          <a href="" class="burger-menu"><i data-feather="menu"></i></a>

          <div class="header-search">
            <i data-feather="search"></i>
            <input type="search" class="form-control" placeholder="What are you looking for?">
          </div><!-- header-search -->
        </div><!-- header-left -->

        <div class="header-right">
          <div class="dropdown dropdown-loggeduser">
            <a href="" class="dropdown-link" data-toggle="dropdown">
              <div class="avatar avatar-sm">
                <img src="../<?= $getuser["picture"] ?>" class="rounded-circle" alt="">
              </div><!-- avatar -->
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              <div class="dropdown-menu-header">
                <div class="media align-items-center">
                  <div class="avatar">
                    <img src="../<?= $getuser["picture"] ?>" class="rounded-circle" alt="">
                  </div><!-- avatar -->
                  <div class="media-body mg-l-10">
                    <h6><?= $getuser["fullname"] ?></h6>
                    <span><?= $getuser["email"] ?></span>
                  </div>
                </div><!-- media -->
              </div>
              <div class="dropdown-menu-body">
                <a href="logout" class="dropdown-item text-danger"><i class="text-danger" data-feather="log-out"></i> Logout</a>
              </div>
            </div><!-- dropdown-menu -->
          </div>
        </div><!-- header-right -->
      </div><!-- header -->