<?php
require_once("../func/session.php");
require_once("../func/sql.php");
require_once("../func/misc.php");
require_once("../func/validate_login.php");
$page_title = "Schedule A Post - ";
require_once("../partials/head2.php");
require_once("../partials/alert.php");
?>

<style>

  #calendar {
    max-width: 90%;
    margin: 0 auto;
  }

</style>
  <body>

    <?php require_once("../partials/sidebar.php") ?>
    <div class="content content-page">

      <?php require_once("../partials/header.php") ?>

      <div class="content-body content-body-calendar">
        <div id="calendar"></div>
      </div><!-- content-body -->
    </div><!-- content -->

    
    <div class="modal calendar-modal-create fade effect-scale" id="modalCreateEvent" role="dialog" aria-hidden="true" style="background-color:rgb(0,0,0,0.5)">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-body pd-20 pd-sm-30">
            <button type="button" class="close pos-absolute t-20 r-20" onclick="closeCreateEvent()" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true"><i data-feather="x"></i></span>
            </button>

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
                    <input id="mediafile" name="mediafile" type="file" value="" class="form-control">
                  </div><!-- col-7 -->
                </div><!-- row -->
              </div><!-- form-group -->
              <div class="form-group">
                <textarea class="form-control" name="post" rows="4" placeholder="Write the contents of your posts"></textarea>
              </div><!-- form-group -->
              <button type="submit" class="btn-block btn btn-primary">Submit</button>
            </form>
          </div><!-- modal-body -->
        </div><!-- modal-content -->
      </div><!-- modal-dialog -->
    </div><!-- modal -->

<div class="modal calendar-modal-event fade effect-scale" id="modalCalendarEvent" role="dialog" style="padding-right: 17px;background-color:rgb(0,0,0,0.5)" aria-hidden>
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header" style="background-color: rgb(1, 104, 250);">
            <h6 id="event-title" class="event-title"></h6>
            <nav class="nav nav-modal-event">
              <a id="edit-event" href="#" class="nav-link"><svg xmlns="http://www.w3.org/2000/svg" height="1em" fill="rgba(255, 255, 255, 0.75)" viewBox="0 0 512 512"><path d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160V416c0 53 43 96 96 96H352c53 0 96-43 96-96V320c0-17.7-14.3-32-32-32s-32 14.3-32 32v96c0 17.7-14.3 32-32 32H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h96c17.7 0 32-14.3 32-32s-14.3-32-32-32H96z"/></svg>
              </a>
              <a onclick="closeEventModal()" class="nav-link" data-dismiss="modal"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></a>
            </nav>
          </div><!-- modal-header -->
          <div class="modal-body">
            <div id="event-media">
              <div class="row row-xs">
              </div>
            </div>
            <label class="tx-uppercase tx-sans tx-11 tx-spacing-1 tx-color-04 tx-medium">Description</label>
            <p id="event-desc" class="event-desc mg-b-40"></p>

            <div class="row row-sm">
              <div class="col-sm-6">
                <label class="tx-uppercase tx-sans tx-11 tx-spacing-1 tx-color-04 tx-medium">Post Date</label>
                <p id="event-postdate" class="event-start-date"></p>
              </div>
              <div class="col-sm-6">
                <label class="tx-uppercase tx-sans tx-11 tx-spacing-1 tx-color-04 tx-medium">Platforms</label>
                <p id="event-platform" class="event-end-date"></p>
              </div><!-- col-6 -->
            </div><!-- row -->
          </div><!-- modal-body -->
        </div><!-- modal-content -->
      </div><!-- modal-dialog -->
    </div>
    




    
    <script src="../lib/jquery/jquery.min.js"></script>
    <script src="../lib/jqueryui/jquery-ui.min.js"></script>
    <script src="../lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../lib/feather-icons/feather.min.js"></script>
    <script src="../lib/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="../lib/js-cookie/js.cookie.js"></script>
    <script src="../lib/moment/min/moment.min.js"></script>
    <!-- <script src="../lib/fullcalendar/fullcalendar.min.js"></script> -->
    <!-- <script src="../lib/select2/js/select2.full.min.js"></script> -->

    <script src="../assets/js/cassie.js"></script>
    <!-- <script src="../assets/js/calendar-events.js"></script>
    <script src="../assets/js/calendar.js"></script> -->

  <script src='../assets/calendar/dist/index.global.js'></script>
  <script>
  function formatPlatform(input) {
  // Split the input string at underscores
  const parts = input.split('_');
  
  // Capitalize the first letter of each part
  const formattedParts = parts.map(part => part.charAt(0).toUpperCase() + part.slice(1));
  
  // Join the formatted parts with commas and spaces
  const formattedString = formattedParts.join(', ');

  return formattedString;
}


    function getEventData(postid) {
      const xhr = new XMLHttpRequest();
      const url = "../func/geteventdata";
      const params = "postid=" + postid;
      xhr.open("POST", url, true);
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
          const response = xhr.responseText.trim();
          try {
            const dataArray = JSON.parse(response);
            const anchorElement = document.querySelector('a#edit-event');
            if (anchorElement) {
              const newHref = "editschedule?id="+dataArray.id+"&edit=true"; // Replace with your desired new URL
              anchorElement.href = newHref;
            } else {
              console.log("Anchor element with href='#' not found.");
            }
            document.getElementById("event-title").innerHTML = dataArray.title;

            document.getElementById("event-desc").innerHTML = dataArray.post;
            document.getElementById("event-postdate").innerHTML = dataArray.postdate;
            document.getElementById("event-platform").innerHTML = formatPlatform(dataArray.platforms);
            if (dataArray.media != undefined) {
                
                if ((dataArray.media != "[]") && (dataArray.media != "")) {
                  const mediaArray = JSON.parse(dataArray.media);
                  const rowContainer = document.createElement('div');
                  rowContainer.classList.add('row', 'row-xs');
                  mediaArray.forEach(value => {
                    const col = document.createElement('div');
                    col.classList.add('col');
                    const img = document.createElement('img');
                    img.src = '../' + value;
                    img.classList.add('img-fluid');
                    img.alt = '';
                    img.style.objectFit = 'cover';
                    img.style.width = '230px';
                    img.style.height = '150px';
                    col.appendChild(img);
                    rowContainer.appendChild(col);
                  });
                  const mediaContainer = document.getElementById('event-media');
                  mediaContainer.appendChild(rowContainer);
                }
            }
          } catch (error) {
            alert("Failed to process the response."+ error);
          }
        }
      };
      xhr.send(params);
    }
     // Function to close the modal
  function closeEventModal() {
  const modal = document.getElementById("modalCalendarEvent");
    // Hide the modal by removing the 'show' class
    modal.classList.remove("show");
    modal.style.display = "none";

    // Set the 'hidden' attribute to true to make the modal inaccessible
    modal.setAttribute("aria-hidden", "true");
    modal.removeAttribute("aria-modal");
    const eventMediaContainer = document.querySelector('#event-media');
    const divToRemove = eventMediaContainer.querySelector('.row.row-xs');
    if (divToRemove) {
      divToRemove.remove();
    }
    document.getElementById('event-media').innerHTML = "";
    // Remove the 'modal-open' class from the body to enable scrolling again
    document.body.classList.remove("modal-open");
  }
  function openEventModal() {
    const modal = document.getElementById("modalCalendarEvent");
      modal.classList.add("show");
      modal.style.display = "block";

      // Remove the 'hidden' attribute to make the modal accessible
      modal.removeAttribute("aria-hidden");
      modal.setAttribute("aria-modal", "true");
      
      // Add the 'modal-open' class to the body to prevent scrolling when the modal is open
      document.body.classList.add("modal-open");
    // Get the modal element by its ID

  }


  
     // Function to close the modal
  function closeCreateEvent() {
  const modal = document.getElementById("modalCreateEvent");
    // Hide the modal by removing the 'show' class
    modal.classList.remove("show");
    modal.style.display = "none";

    // Set the 'hidden' attribute to true to make the modal inaccessible
    modal.setAttribute("aria-hidden", "true");
    modal.removeAttribute("aria-modal");

    // Remove the 'modal-open' class from the body to enable scrolling again
    document.body.classList.remove("modal-open");
  }
  function openCreateEvent() {
    const modal = document.getElementById("modalCreateEvent");
      modal.classList.add("show");
      modal.style.display = "block";

      // Remove the 'hidden' attribute to make the modal accessible
      modal.removeAttribute("aria-hidden");
      modal.setAttribute("aria-modal", "true");
      
      // Add the 'modal-open' class to the body to prevent scrolling when the modal is open
      document.body.classList.add("modal-open");
    // Get the modal element by its ID

  }


  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      initialDate: new Date,
      navLinks: true, // can click day/week names to navigate views
      selectable: true,
      selectMirror: true,
      select: function(arg) {
        openCreateEvent();
      },
      eventClick: function(arg) {
        getEventData(arg.event.postid)
        openEventModal();
      },
      editable: true,
      dayMaxEvents: true,
      events: [<?php
        $selectposts = "SELECT * FROM posts WHERE status='Pending' ORDER BY id DESC";
        $queryposts = mysqli_query($con, $selectposts);
        while ($getposts = mysqli_fetch_assoc($queryposts)) {
        ?>
        {
          title: "<?= $getposts["title"] ?>",
          start: "<?= str_replace(" ", "T", $getposts["postdate"]) ?>",
          postid: "<?= $getposts["id"] ?>"
        },<?php
        }
        ?>
      ]
    });

    calendar.render();
  });

</script>
  </body>
</html>