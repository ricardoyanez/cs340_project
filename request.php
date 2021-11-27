<!--
Author: Ricardo Yanez <yanezr@oregonstate.edu>
Date: 11/23/2021

Course: CS 340 (Introduction to Databases)
 -->

<?php

$conn = mysqli_connect("localhost", "cs340_yanezr", "0735") or die(mysqli_error());
mysqli_select_db($conn, "cs340_yanezr") or die(mysqli_error());

if( isset($_POST['ip']) && !empty($_POST['ip']) AND isset($_POST['email']) && !empty($_POST['email']) ) {

  $delist_ip = mysqli_escape_string($conn, $_POST['ip']);
  $email = mysqli_escape_string($conn, $_POST['email']);

  $datetime = date('Y-m-d H:i:s');

  // insert DELIST data
  $sql = "INSERT INTO delist (delist_id,delist_ip,delist_email,delist_date) VALUES (
    NULL,'".$delist_ip."','".$email."','".$datetime."')";
  mysqli_query($conn,$sql) or die(mysqli_error($conn));

  // get delist_id
  $search = mysqli_query($conn,"SELECT last_insert_id()") or die(mysqli_error($conn));
  $match  = mysqli_num_rows($search);
  if ( $match == 1 ) {
    while ( $row = mysqli_fetch_array($search) ) {
      $delist_id = mysqli_escape_string($conn, $row[0]);
    }
  }

  $hash_str = $delist_ip.time();
  $hash = md5($hash_str);

  // insert CONFIRMATION data
  $sql = "INSERT INTO confirmation_delist (confirmation_id,confirmation_hash) VALUES (
    '".$delist_id."','".$hash."')";
  mysqli_query($conn,$sql) or die(mysqli_error($conn));

  // insert ADMIN data
  $sql = "INSERT INTO admin (admin_id,admin_ip,admin_email) VALUES (
    '".$delist_id."','".$delist_ip."','".$email."')";
  mysqli_query($conn,$sql) or die(mysqli_error($conn));

  header("Location: delist");
}

?>
