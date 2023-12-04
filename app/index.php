<?php
require_once("../func/session.php");
require_once("../func/sql.php");
require_once("../func/misc.php");
require_once("../func/validate_login.php");
$page_title = "";
require_once("../partials/head2.php");
require_once("../partials/alert.php");
?>
  <body>

    <?php require_once("../partials/sidebar.php") ?>
    <div class="content content-page">

      <?php require_once("../partials/header.php") ?>

      <div class="content-body mt-3">
        <div class="card card-hover card-task-one">
          <div class="card-body">
            <div class="row">
              <div class="col-sm-6 col-md-3">
                <h6 class="card-title">Total Posts</h6>
                <div class="d-flex align-items-center justify-content-between mg-b-10">
                  <h1 class="card-value"><?php
                  $stposts = "SELECT * FROM posts";
                  $qtposts = mysqli_query($con, $stposts);
                  echo mysqli_num_rows($qtposts)
                  ?></h1>
                  <div class="chart-wrapper">
                    <div id="flotChart1" class="flot-chart"></div>
                  </div>
                </div> 
                <p class="card-desc">the total number of post that has been automatically generated or manually scheduled, that have both been posted or awaiting due posting.</p>
              </div><!-- col -->
              <div class="col-sm-6 col-md-3 mg-t-20 mg-sm-t-0">
                <h6 class="card-title">Posted Posts</h6>
                <div class="d-flex align-items-center justify-content-between mg-b-10">
                  <h1 class="card-value"><?php
                  $sptposts = "SELECT * FROM posts WHERE status='Posted'";
                  $qptposts = mysqli_query($con, $sptposts);
                  echo mysqli_num_rows($qptposts)
                  ?></h1>
                  <div class="chart-wrapper">
                    <div id="flotChart2" class="flot-chart"></div>
                  </div>
                </div>
                <p class="card-desc">the total number of post that has been automatically generated or manually scheduled that has been posted.</p>
              </div><!-- col -->
              <div class="col-sm-6 col-md-3 mg-t-20 mg-md-t-0">
                <h6 class="card-title">Scheduled Posts</h6>
                <div class="d-flex align-items-center justify-content-between mg-b-10">
                  <h1 class="card-value"><?php
                  $spdposts = "SELECT * FROM posts WHERE status='Pending'";
                  $qpdposts = mysqli_query($con, $spdposts);
                  echo mysqli_num_rows($qpdposts)
                  ?></h1>
                  <div class="chart-wrapper">
                    <div id="flotChart3" class="flot-chart"></div>
                  </div>
                </div>
                <p class="card-desc">the total number of post that has been automatically generated or manually scheduled, awaiting due posting.</p>
              </div><!-- col -->
              <div class="col-sm-6 col-md-3 mg-t-20 mg-md-t-0">
                <h6 class="card-title">Next Post</h6>
                <div class="d-flex align-items-center justify-content-between mg-b-10">
                  <h1 class="card-value"><span class="tx-color-03 me-3">in</span>&nbsp;<?php
                  $snpost = "SELECT * FROM posts WHERE status='Pending' ORDER BY ABS(TIMESTAMPDIFF(SECOND, postdate, NOW())) ASC";
                  $qnpost = mysqli_query($con, $snpost);
                  $gnpost = mysqli_fetch_assoc($qnpost);
                  echo getTimeDistance($gnpost["postdate"]);
                  ?></h1>
                  <div class="chart-wrapper">
                    <div id="flotChart4" class="flot-chart"></div>
                  </div>
                </div>
                <p class="card-desc">the total amount of time before the next scheduled post.</p>
              </div><!-- col -->
            </div><!-- row -->
          </div><!-- card-body -->
        </div><!-- card -->
        <div class="row row-sm mg-t-15 mg-sm-t-20">
          <div class="col-xl-4 mg-t-15 mg-sm-t-20 mg-xl-t-0">
            <div class="card card-hover card-projects">
              <div class="card-header bg-transparent pd-y-15 pd-l-15 pd-r-10">
                <h6 class="card-title mg-b-0">Recently Posted</h6>
                <nav class="nav">
                  <a href="" class="link-gray-500"><i data-feather="help-circle" class="svg-16"></i></a>
                  <a href="" class="link-gray-500"><i data-feather="more-vertical" class="svg-16"></i></a>
                </nav>
              </div><!-- card-header -->
              <ul class="list-group list-group-flush">
                <?php
                $selectposted = "SELECT * FROM posts WHERE status='Posted' ORDER BY ABS(TIMESTAMPDIFF(SECOND, postdate, NOW())) ASC LIMIT 2";
                $queryposted = mysqli_query($con, $selectposted);
                while ($getposted = mysqli_fetch_assoc($queryposted)) {
                ?>
                <li class="list-group-item">
                  <nav class="nav nav-card-icon">
                    <a href=""><i data-feather="activity" class="svg-16"></i></a>
                    <a href=""><i data-feather="bar-chart-2" class="svg-16"></i></a>
                    <a href=""><i data-feather="chevron-down" class="svg-16"></i></a>
                  </nav>
                  <div class="media">
                    <div class="media-body mg-l-10 mg-sm-l-15">
                      <p class="tx-13 tx-color-04 mg-b-5"><?= implode(', ', stringToArray($getposted["platforms"])); ?></p>
                      <h5 class="tx-color-01 mg-b-0"><?= $getposted["title"] ?></h5>
                    </div><!-- media-body -->
                  </div><!-- media -->
                  <p class="project-desc"><?php
                                        $postText = $getposted["post"];
                                        $maxWords = 20;
                                        $words = explode(' ', $postText);
                                        if (count($words) > $maxWords) {
                                            echo implode(' ', array_slice($words, 0, $maxWords)).'...';
                                        } else {
                                            echo $postText;
                                        } 
                                        ?></p>
                  <small class="project-deadline">Posted: <?= date("F d, Y H:i", strtotime($getposted["postdate"])) ?></small>
                </li>
                <?php
                }
                ?>
              </ul>
            </div>
          </div><!-- col -->
          <div class="col-xl-4 mg-t-15 mg-sm-t-20 mg-xl-t-0">
            <div class="card card-hover card-projects">
              <div class="card-header bg-transparent pd-y-15 pd-l-15 pd-r-10">
                <h6 class="card-title mg-b-0">Posting Soon</h6>
                <nav class="nav">
                  <a href="" class="link-gray-500"><i data-feather="help-circle" class="svg-16"></i></a>
                  <a href="" class="link-gray-500"><i data-feather="more-vertical" class="svg-16"></i></a>
                </nav>
              </div><!-- card-header -->
              <ul class="list-group list-group-flush">
                <?php
                $selectposted = "SELECT * FROM posts WHERE status='Pending' ORDER BY ABS(TIMESTAMPDIFF(SECOND, postdate, NOW())) ASC LIMIT 2";
                $queryposted = mysqli_query($con, $selectposted);
                while ($getposted = mysqli_fetch_assoc($queryposted)) {
                ?>
                <li class="list-group-item">
                  <nav class="nav nav-card-icon">
                    <a href=""><i data-feather="activity" class="svg-16"></i></a>
                    <a href=""><i data-feather="bar-chart-2" class="svg-16"></i></a>
                    <a href=""><i data-feather="chevron-down" class="svg-16"></i></a>
                  </nav>
                  <div class="media">
                    <div class="media-body mg-l-10 mg-sm-l-15">
                      <p class="tx-13 tx-color-04 mg-b-5"><?= implode(', ', stringToArray($getposted["platforms"])); ?></p>
                      <h5 class="tx-color-01 mg-b-0"><?= $getposted["title"] ?></h5>
                    </div><!-- media-body -->
                  </div><!-- media -->
                      <div class="row row-xs">
                        <?php
                        if (json_decode($getposted["media"])) {
                          $media = json_decode($getposted["media"]);
                          foreach ($media as $value) {
                          ?>
                          <div class="col">
                              <img
                                src="../<?= $value ?>"
                                class="img-fluid"
                                alt=""
                                style="object-fit: cover; width: 230px;height: 150px;"
                              />
                          </div>
                          <?php
                          }
                        }
                        ?>
                      </div><?php if (json_decode($getposted["media"])) { echo "<br>"; } ?>
                  <p class="project-desc"><?php
                                        $postText = $getposted["post"];
                                        $maxWords = 20;
                                        $words = explode(' ', $postText);
                                        if (count($words) > $maxWords) {
                                            echo implode(' ', array_slice($words, 0, $maxWords)).'...';
                                        } else {
                                            echo $postText;
                                        } 
                                        ?></p>
                  <small class="project-deadline">Post Date: <?= date("F d, Y H:i", strtotime($getposted["postdate"])) ?></small>
                </li>
                <?php
                }
                ?>
              </ul>
            </div>
          </div><!-- col -->
          <div class="col-xl-4 mg-t-15 mg-sm-t-20 mg-xl-t-0">
            <div class="ht-100p bd-sm-l pd-x-15 pd-sm-x-20 pd-t-15 pd-sm-t-20 pd-b-15">
              <div id="datepicker1"></div>
              <a href="schedule" class="btn pd-t-10 btn-brand-01 btn-uppercase tx-spacing-2 mg-x-5" style="margin-top: 20px">Schedule A Post</a>
            </div>
          </div><!-- col -->
        </div><!-- row -->
      </div><!-- content-body -->
    </div><!-- content -->
<?php
require_once("../partials/footer.php");
?>