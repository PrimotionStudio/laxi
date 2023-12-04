<?php
if (isset($_SESSION["alert"])) {
?>
    
<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body mt-3">
                <p class=" text-center"><?= $_SESSION["alert"] ?></p>
            </div>
            <div class="modal-footer text-center">
                <button type="button" class="btn btn-primary text-white mx-auto" onclick="closeModal()">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal-backdrop fade show" id="backdrop" style="display: none;"></div>


    <script>
    document.getElementById("backdrop").style.display = "block"
    document.getElementById("alertModal").style.display = "block";
    document.getElementById("alertModal").classList.add("show");
    
function closeModal() {
    document.getElementById("backdrop").style.display = "none"
    document.getElementById("alertModal").style.display = "none"
    document.getElementById("alertModal").classList.remove("show")
}
// Get the modal
var modal = document.getElementById('alertModal');

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    closeModal()
  }
}
    </script>
<?php
    unset($_SESSION["alert"]);
}
?>