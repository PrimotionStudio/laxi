<?php
require_once("../func/session.php");
require_once("../func/sql.php");
require_once("../func/misc.php");
require_once("../func/validate_login.php");
if (isset($_GET["edit"]) && $_GET["edit"] == "true") {
  $result = querytable($con, "posts", "id", $_GET["id"], "userid");
  if (isset($_GET["id"]) && strpos($result, "Cannot Find") === false) {
    if ($result == $userid) {
    } else {
      alert("An error occured", [], "schedule");
    }
  } else {
    alert("An error occured", [], "schedule");
  }
} else {
  alert("An error occured", [], "schedule");
}
$page_title = "Edit A Post - ";
require_once("../partials/head2.php");
require_once("../partials/alert.php");
?>
  <body>

    <?php require_once("../partials/sidebar.php") ?>
    <div class="content content-page">

      <?php require_once("../partials/header.php") ?>

      <div class="content-body mt-3">
        <div class="row row-sm">
          <div class="col-xl-2 mb-3"></div>
          <div class="col-xl-6 mb-3">
            <div class="card card-body">
              

            <h5 class="tx-18 tx-sm-20 mg-b-20 mg-sm-b-30">Edit A Post</h5>

            <form id="formCalendar" method="post" action="../func/editschedule" enctype="multipart/form-data">
              <input type="hidden" name="postid" value="<?= $_GET["id"] ?>">
              <div class="form-group">
                <label class="tx-uppercase tx-sans tx-11 tx-spacing-1 tx-color-04 tx-medium">Title</label>
                <input type="text" name="title" class="form-control" value="<?= querytable($con, "posts", "id", $_GET["id"], "title") ?>" placeholder="Add title or leave blank">
              </div><!-- form-group -->
              <div class="form-group d-flex align-items-center">
                <?php
                $platforms = stringToArray(querytable($con, "posts", "id", $_GET["id"], "platforms"));
                ?>
                <div class="form-check mx-2">
                  <input type="checkbox" id="platform" name="platform[]" value="Facebook" class="form-check-input" <?php
                  if (in_array('Facebook', $platforms)) {echo "checked ";}?>multiple>
                  <label class="form-check-label" for="facebook">Facebook</label>
                </div>
                <div class="form-check mx-2">
                  <input type="checkbox" id="platform" name="platform[]" value="Instagram" disabled class="form-check-input" <?php
                  if (in_array('Instagram', $platforms)) {echo "checked ";}?>multiple>
                  <label class="form-check-label" for="instagram">Instagram</label>
                </div>
                <br>
                <div class="form-check mx-2">
                  <input type="checkbox" id="platform" name="platform[]" value="Twitter" class="form-check-input" <?php
                  if (in_array('Twitter', $platforms)) {echo "checked ";}?>multiple>
                  <label class="form-check-label" for="twitter">Twitter</label>
                </div>
                <div class="form-check">
                  <input type="checkbox" id="platform" name="platform[]" value="LinkedIn" class="form-check-input" <?php
                  if (in_array('LinkedIn', $platforms)) {echo "checked ";}?>multiple>
                  <label class="form-check-label" for="linkedin">LinkedIn</label>
                </div>
              </div><!-- form-group -->
              <div class="form-group mg-t-30">
                <label class="tx-uppercase tx-sans tx-11 tx-spacing-1 tx-color-04 tx-medium">Post Date</label>
                <div class="row row-xs">
                  <div class="col-12">
                    <input id="postdate" name="postdate" type="datetime-local" value="<?= date('Y-m-d\TH:i', strtotime(querytable($con, "posts", "id", $_GET["id"], "postdate"))) ?>" class="form-control" placeholder="Select date and time">
                  </div><!-- col-7 -->
                </div><!-- row -->
              </div><!-- form-group -->
              <div class="form-group">
                <label class="tx-uppercase tx-sans tx-11 tx-spacing-1 tx-color-04 tx-medium">Upload Media File (.jpg, .png, .jpeg)</label>
                <div class="row row-xs">
                  <div class="col-12">
                    <input id="mediafile" name="mediafile" type="file" value="" class="form-control">
                  </div><!-- col-7 -->
                </div><!-- row -->
              </div><!-- form-group -->
              <div class="form-group">
                <textarea class="form-control" name="post" rows="10" placeholder="Write the contents of your posts"><?= querytable($con, "posts", "id", $_GET["id"], "post") ?></textarea>
              </div><!-- form-group -->
              <button type="submit" class="btn-block btn btn-primary">Submit</button>
            </form>
            </div><!-- card -->
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
        if (xhr.responseText == "Posted") {
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
    const seeLessLink =
      event.target.previousElementSibling.previousElementSibling;

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