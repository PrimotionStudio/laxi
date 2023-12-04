<?php
require_once("../func/session.php");
session_destroy();
header("location: ../login");
?>