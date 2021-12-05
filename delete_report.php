<!--
Author: Ricardo Yanez <yanezr@oregonstate.edu>
Date: 11/23/2021

Course: CS 340 (Introduction to Databases)
 -->

<?php

if ( ($_SERVER['HTTP_REFERER'] == "https://cs340.calel.org/ip") ) {
  if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    if( isset($_POST['report_id']) && !empty($_POST['report_id']) ) {

      $conn = mysqli_connect("localhost", "cs340_yanezr", "0735") or die(mysqli_error());
      mysqli_select_db($conn, "cs340_yanezr") or die(mysqli_error());

      $report_id = mysqli_escape_string($conn, $_POST['report_id']);

      // delete confirmation
      $sql = "DELETE FROM confirmation_report  WHERE confirmation_id='".$report_id."'";
      mysqli_query($conn,$sql) or die(mysqli_error($conn));

      // delete spam
      $sql = "DELETE FROM spam WHERE spam_id='".$report_id."'";
      mysqli_query($conn,$sql) or die(mysqli_error($conn));

      // delete ip
      $sql = "DELETE FROM ip WHERE ip_id='".$report_id."'";
      mysqli_query($conn,$sql) or die(mysqli_error($conn));

      // delete report
      $sql = "DELETE FROM report WHERE report_id='".$report_id."'";
      mysqli_query($conn,$sql) or die(mysqli_error($conn));

      // close DB connection
      mysqli_close($conn);

      header("Location: ip");
    }
  }
}

?>
