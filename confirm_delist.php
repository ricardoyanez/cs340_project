<!--
Author: Ricardo Yanez <yanezr@oregonstate.edu>
Date: 11/23/2021

Course: CS 340 (Introduction to Databases)
 -->

<?php

$conn = mysqli_connect("localhost", "cs340_yanezr", "0735") or die(mysqli_error());
mysqli_select_db($conn, "cs340_yanezr") or die(mysqli_error());

if( isset($_POST['delist_id']) && !empty($_POST['delist_id']) AND isset($_POST['delist_ip']) && !empty($_POST['delist_ip']) ) {

  $delist_id = mysqli_escape_string($conn, $_POST['delist_id']);
  $delist_ip = mysqli_escape_string($conn, $_POST['delist_ip']);

  $datetime = date('Y-m-d H:i:s');

  // update confirmation
  $sql = "UPDATE confirmation_delist SET confirmation_flag='1', confirmation_date='".$datetime."' WHERE confirmation_id='".$delist_id."'";
  mysqli_query($conn,$sql) or die(mysqli_error($conn));

  // delist IP
  $sql = "SELECT ip_id FROM ip WHERE ip_address='".$delist_ip."' AND ip_listed='1'";
  $search = mysqli_query($conn,$sql) or die(mysqli_error($conn));
  $match  = mysqli_num_rows($search);
  if ( $match > 0 ) {
    while ( $row = mysqli_fetch_array($search) ) {
      $ip_id = mysqli_escape_string($conn, $row[0]);
      $sql = "UPDATE ip SET ip_listed='0', delist_id='".$delist_id."' WHERE ip_id='".$ip_id."'";
      mysqli_query($conn,$sql) or die(mysqli_error($conn));
    }

  }

  header("Location: delist");
}

?>
