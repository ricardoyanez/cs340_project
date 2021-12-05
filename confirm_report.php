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
      $datetime = date('Y-m-d H:i:s');

      // update confirmation
      $sql = "UPDATE confirmation_report SET confirmation_flag='1', confirmation_date='".$datetime."' WHERE confirmation_id='".$report_id."'";
      mysqli_query($conn,$sql) or die(mysqli_error($conn));

      // update IP table
      $sql = "UPDATE ip SET ip_listed='1' WHERE ip_id='".$report_id."'";
      mysqli_query($conn,$sql) or die(mysqli_error($conn));

      // close DB connection
      mysqli_close($conn);

      header("Location: ip");
    }
  }
}

?>
