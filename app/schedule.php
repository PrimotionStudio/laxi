<?php
require_once("../func/session.php");
require_once("../func/sql.php");
require_once("../func/misc.php");
require_once("../func/validate_login.php");
$page_title = "Schedule A Post - ";
require_once("../partials/head2.php");
require_once("../partials/alert.php");
?>
  <body>

    <?php require_once("../partials/sidebar.php") ?>
    <div class="content content-page">

      <?php require_once("../partials/header.php") ?>

      <div class="content-body mt-3">
        <div class="row row-sm">
          <div class="col-xl-4 mb-3">

            <div class="card card-body mb-3">
              <h5 class="tx-18 tx-sm-20 mg-b-20 mg-sm-b-30">Generate Post</h5>
              <form action="../func/generatepost" method="post">
                <div class="form-group">
                  <label class="tx-uppercase tx-sans tx-11 tx-spacing-1 tx-color-04 tx-medium">Write a topic to generate post on.</label>
                  <div class="row row-xs">
                    <div class="col-12">
                      <input type="text" class="form-control" name="topic" value="How to stay relevant in the digital world using tech skills" placeholder="Write a topic to generate post on.">
                    </div><!-- col-7 -->
                  </div><!-- row -->
                </div><!-- form-group -->
                <div class="form-group">
                  <label class="tx-uppercase tx-sans tx-11 tx-spacing-1 tx-color-04 tx-medium">Write a subtopic or heading to assist the topic</label>
                  <div class="row row-xs">
                    <div class="col-12">
                      <input type="text" class="form-control" name="heading" value="Programming Languages(PHP, Python, Nodejs, React, Golang, etc), Graphic Design, Cyber Security" placeholder="Write a subtopic or heading to assist the topic">
                    </div><!-- col-7 -->
                  </div><!-- row -->
                </div><!-- form-group -->
                <div class="form-group">
                  <label class="tx-uppercase tx-sans tx-11 tx-spacing-1 tx-color-04 tx-medium">Number of posts to generate: <b><span id="postValue" class="tx-16">10</span></b></label>
                  <div class="row row-xs">
                    <div class="col-12">
                      <input type="range" min="4" max="32" class="form-control" name="number" value="32" id="postRange">
                    </div><!-- col-7 -->
                  </div><!-- row -->
                </div><!-- form-group -->
                <div class="form-group d-flex align-items-center">
                  <div class="form-check mx-2">
                    <input type="checkbox" id="platform" name="platform[]" value="Facebook" class="form-check-input" checked multiple>
                    <label class="form-check-label" for="facebook">Facebook</label>
                  </div>
                  <div class="form-check">
                    <input type="checkbox" id="platform" name="platform[]" value="LinkedIn" class="form-check-input" checked multiple>
                    <label class="form-check-label" for="linkedin">LinkedIn</label>
                  </div>
                </div><!-- form-group -->

                <script>
                  // Get the range input and the element where the value will be displayed
                  const rangeInput = document.getElementById('postRange');
                  const valueElement = document.getElementById('postValue');

                  // Add an event listener to update the displayed value when the input changes
                  rangeInput.addEventListener('input', () => {
                    valueElement.textContent = rangeInput.value;
                  });

                  // Initialize the displayed value
                  valueElement.textContent = rangeInput.value;
                </script>

                <button type="submit" class="btn-block btn btn-primary">Generate Post</button>
              </form>
            </div>

            <div class="card card-body">
            <h5 class="tx-18 tx-sm-20 mg-b-20 mg-sm-b-30">Schedule A Post</h5>
            <form id="formCalendar" method="post" action="../func/schedule" enctype="multipart/form-data">
              <div class="form-group">
                <label class="tx-uppercase tx-sans tx-11 tx-spacing-1 tx-color-04 tx-medium">Title</label>
                <input type="text" name="title" class="form-control" title="This won't get posted" placeholder="Add title or leave blank">
              </div><!-- form-group -->
              <div class="form-group d-flex align-items-center">
                <div class="form-check mx-2">
                  <input type="checkbox" id="platform" name="platform[]" value="Facebook" class="form-check-input" checked multiple>
                  <label class="form-check-label" for="facebook">Facebook</label>
                </div>
                <div class="form-check mx-2">
                  <input type="checkbox" id="platform" name="platform[]" value="Instagram" class="form-check-input" disabled multiple>
                  <label class="form-check-label" for="instagram">Instagram</label>
                </div>
                <br>
                <div class="form-check mx-2">
                  <input type="checkbox" id="platform" name="platform[]" value="Twitter" class="form-check-input" multiple>
                  <label class="form-check-label" for="twitter">Twitter</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" id="platform" name="platform[]" value="LinkedIn" class="form-check-input" multiple>
                  <label class="form-check-label" for="linkedin">LinkedIn</label>
                </div>
              </div><!-- form-group -->
              <div class="form-group mg-t-30">
                <label class="tx-uppercase tx-sans tx-11 tx-spacing-1 tx-color-04 tx-medium">Post Date</label>
                <div class="row row-xs">
                  <div class="col-12">
                    <input id="postdate" name="postdate" type="datetime-local" value="<?php
$activeTimes = [
    "Mon" => ["10:00", "15:30", "20:45"],
    "Tue" => ["11:15", "16:00", "21:20"],
    "Wed" => ["09:30", "14:45", "19:15"],
    "Thu" => ["08:45", "13:30", "18:00"],
    "Fri" => ["10:30", "15:00", "20:30"],
    "Sat" => ["11:00", "16:15", "21:45"],
    "Sun" => ["09:15", "14:00", "19:30"]
];
$dayNames = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
$currentDayOfWeek = date("w");
$currentHourMinute = date("H:i");
$selectedDay = $dayNames[$currentDayOfWeek];
$postTime = null;
foreach ($activeTimes[$selectedDay] as $time) {
  if ($currentHourMinute <= $time) {
    $postTime = $time;
    break;
  }
}
if ($postTime === null) {
  $nextDayIndex = ($currentDayOfWeek + 1) % 7;
  $selectedDay = $dayNames[$nextDayIndex];
  $postTime = $activeTimes[$selectedDay][0];
  $postdate = date("Y-m-d", strtotime("+1 days")) . " " . $postTime;
} else {
  $postdate = date("Y-m-d") . " " . $postTime;
}
        echo $postdate; ?>" class="form-control" placeholder="Select date and time">
                  </div><!-- col-7 -->
                </div><!-- row -->
              </div><!-- form-group -->
              <div class="form-group">
                <label class="tx-uppercase tx-sans tx-11 tx-spacing-1 tx-color-04 tx-medium">Upload Media File (.jpg, .png, .jpeg)</label>
                <div class="row row-xs">
                  <div class="col-12">
                    <input id="mediafile" name="mediafile[]"  type="file" value="" class="form-control" multiple>
                  </div><!-- col-7 -->
                </div><!-- row -->
              </div><!-- form-group -->
              <div class="form-group">
                <textarea class="form-control" name="post" rows="4" placeholder="Write the contents of your posts"></textarea>
              </div><!-- form-group -->
              <button type="submit" class="btn-block btn btn-primary">Submit</button>
            </form>
            </div><!-- card -->
          </div><!-- col -->
          <div class="col-xl-8">
            <?php
            $selectposts = "SELECT * FROM posts WHERE status='Pending' ORDER BY ABS(TIMESTAMPDIFF(SECOND, postdate, NOW())) ASC";
            $queryposts = mysqli_query($con, $selectposts);
            while ($getposts = mysqli_fetch_assoc($queryposts)) {
            ?>
            <div id="post<?= $getposts["id"] ?>" class="timeline-item">
              <div class="row row-sm">
                <div class="col-sm-3 col-lg-2">
                  <div class="date-wrapper">
                    <span class="timeline-date tx-11"><?= formatDateTime($getposts["postdate"], "date") ?></span>
                    <h6 class="timeline-time"><?= formatDateTime($getposts["postdate"], "time") ?></h6>
                  </div>
                </div>
                <div class="col-sm-9">
                  <div class="timeline-body">
                    <div class="timeline-header">
                      <h6><?= implode(', ', stringToArray($getposts["platforms"])); ?></h6>
                    </div><!-- timeline-header -->
                    <div class="card card-timeline card-timeline-note card-timeline-photo">
                      <h5><?= $getposts["title"] ?></h5>
                      <div class="marker pos-absolute t-10 r-10" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="false" aria-expanded="false"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line></svg></div>
                      <div class="dropdown">
                        <div class="dropdown-menu" style="border-radius:10px;" aria-labelledby="dropdownMenuButton">
                          <button class="postnow dropdown-item">Post Now</button>
                          <a href="editschedule?id=<?= $getposts["id"] ?>&edit=true" class="dropdown-item">Edit</a>
                          <button class="deletepost dropdown-item text-danger">Delete</>
                        </div>
                      </div>
                      <div class="row row-xs">
                        <?php
                        if (json_decode($getposts["media"])) {
                          $media = json_decode($getposts["media"]);
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
                      </div><?php if (json_decode($getposts["media"])) { echo "<br>"; } ?>
                      <p class="mg-b-0"><?php
                                        $postText = $getposts["post"];
                                        $maxWords = 20;
                                        $words = explode(' ', $postText);
                                        if (count($words) > $maxWords) {
                                            echo '<span class="some-text">'.implode(' ', array_slice($words, 0, $maxWords)).'...</span>';
                                            echo '<span class="full-text d-none">' . $postText . '</span>';
                                            echo '<span class="see-more fw-600 text-primary ms-2 cursor-pointer">See more</span>';
                                        } else {
                                            echo $postText;
                                        } 
                                        ?></p>
                    </div>
                  </div><!-- timeline-body -->
                </div>
              </div><!-- row -->
            </div><!-- timeline -->
            <?php
            }
            ?>
          </div><!-- col -->
        </div><!-- row -->
      </div><!-- content-body -->
    </div><!-- content -->
    
    <div id="alert" class="alert alert-info fixed-bottom ms-3 mb-3 fade show" style="display: none; margin-bottom: 20px; width:400px;">
        <span id="alerttxt"></span>
        <button type="button" class="border-0 bg-transparent" style="float:right" data-dismiss="alert">x</button>
    </div>
<script>
callalert("Hello, how are you doing today&nbsp;👋🖐.");
function callalert(alerttxt) {
  var callAlert = document.getElementById("alert");
  callAlert.style.display = "block";
  setTimeout(function () {
  callAlert.style.display = "none";
  }, 5000);
  callAlert.querySelector("span#alerttxt").innerHTML = alerttxt;
}
const postnows = document.querySelectorAll(".postnow");
postnows.forEach((button) => {
  button.addEventListener("click", function (event) {
    const postcard = event.currentTarget.closest(".timeline-item");
    const xhr = new XMLHttpRequest();
    const url = "../func/postnow";
    const postid = postcard.id;
    const params = "post=" + postid;
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
        if (xhr.responseText == "Refresh") {
          postcard.remove();
          window.location.reload();
        }
      }
    };
    xhr.send(params);
  });
});

const deleteposts = document.querySelectorAll(".deletepost");
deleteposts.forEach((button) => {
  button.addEventListener("click", function (event) {
    const postcard = event.currentTarget.closest(".timeline-item");
    const xhr = new XMLHttpRequest();
    const url = "../func/deletepost";
    const postid = postcard.id;
    const params = "post=" + postid;
    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4 && xhr.status === 200) {
        if (xhr.responseText == "Deleted") {
          postcard.remove();
          callalert(xhr.responseText);
        } else {
          callalert(xhr.responseText);
        }
      }
    };
    xhr.send(params);
  });
});

const seeMoreLinks = document.querySelectorAll(".see-more");
seeMoreLinks.forEach((link) => {
  link.addEventListener("click", function (event) {
    const postText = event.target.previousElementSibling;
    const seeLessLink = event.target.previousElementSibling.previousElementSibling;

    if (postText.classList.contains("d-none")) {
      postText.classList.remove("d-none");
      seeLessLink.classList.remove("d-none");
      event.target.textContent = "See less";
    } else {
      postText.classList.add("d-none");
      seeLessLink.classList.add("d-none");
      event.target.textContent = "See more";
    }
  });
});

</script>
<?php
require_once("../partials/footer.php");
?>