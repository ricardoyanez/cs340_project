<!--
Author: Ricardo Yanez <yanezr@oregonstate.edu>
Date: 11/23/2021

Course: CS 340 (Introduction to Databases)
 -->

<?php

if ( ($_SERVER['HTTP_REFERER'] == "https://cs340.calel.org/request") ) {
  if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    if( isset($_POST['delist_id']) && !empty($_POST['delist_id']) ) {

      $conn = mysqli_connect("localhost", "cs340_yanezr", "0735") or die(mysqli_error());
      mysqli_select_db($conn, "cs340_yanezr") or die(mysqli_error());

      $delist_id = mysqli_escape_string($conn, $_POST['delist_id']);

      // delete confirmation
      $sql = "DELETE FROM confirmation_delist  WHERE confirmation_id='".$delist_id."'";
      mysqli_query($conn,$sql) or die(mysqli_error($conn));

      // delete admin
      $sql = "DELETE FROM admin WHERE admin_id='".$delist_id."'";
      mysqli_query($conn,$sql) or die(mysqli_error($conn));

      // delete delist
      $sql = "DELETE FROM delist WHERE delist_id='".$delist_id."'";
      mysqli_query($conn,$sql) or die(mysqli_error($conn));

      // close DB connection
      mysqli_close($conn);

      header("Location: delist");
    }
  }
}

?>
