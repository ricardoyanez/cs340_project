<!--
Author: Ricardo Yanez <yanezr@oregonstate.edu>
Date: 11/23/2021

Course: CS 340 (Introduction to Databases)
 -->

<?php

$conn = mysqli_connect("localhost", "cs340_yanezr", "0735") or die(mysqli_error());
mysqli_select_db($conn, "cs340_yanezr") or die(mysqli_error());

if( isset($_POST['delist_id']) && !empty($_POST['delist_id']) ) {

  $delist_id = mysqli_escape_string($conn, $_POST['delist_id']);

  // delete confirmation
  $sql = "DELETE from confirmation_delist  WHERE confirmation_id='".$delist_id."'";
  mysqli_query($conn,$sql) or die(mysqli_error($conn));

  // delete admin
  $sql = "DELETE from admin WHERE admin_id='".$delist_id."'";
  mysqli_query($conn,$sql) or die(mysqli_error($conn));

  // delete delist
  $sql = "DELETE from delist WHERE delist_id='".$delist_id."'";
  mysqli_query($conn,$sql) or die(mysqli_error($conn));

  header("Location: delist");
}

?>
