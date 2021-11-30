<!--
Author: Ricardo Yanez <yanezr@oregonstate.edu>
Date: 11/23/2021

Course: CS 340 (Introduction to Databases)
 -->

<?php

$conn = mysqli_connect("localhost", "cs340_yanezr", "0735") or die(mysqli_error());
mysqli_select_db($conn, "cs340_yanezr") or die(mysqli_error());

if( isset($_POST['report_id']) && !empty($_POST['report_id']) ) {

  $report_id = mysqli_escape_string($conn, $_POST['report_id']);

  // delete confirmation
  $sql = "DELETE from confirmation_report  WHERE confirmation_id='".$report_id."'";
  mysqli_query($conn,$sql) or die(mysqli_error($conn));

  // delete spam
  $sql = "DELETE from spam WHERE spam_id='".$report_id."'";
  mysqli_query($conn,$sql) or die(mysqli_error($conn));

  // delete ip
  $sql = "DELETE from ip WHERE ip_id='".$report_id."'";
  mysqli_query($conn,$sql) or die(mysqli_error($conn));

  // delete report
  $sql = "DELETE from report WHERE report_id='".$report_id."'";
  mysqli_query($conn,$sql) or die(mysqli_error($conn));
  header("Location: ip");
}

?>
