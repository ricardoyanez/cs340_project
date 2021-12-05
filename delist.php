<!--
Author: Ricardo Yanez <yanezr@oregonstate.edu>
Date: 11/23/2021

Course: CS 340 (Introduction to Databases)
 -->

<!DOCTYPE html>
<html>
  <head>
    <title>Report and Delist Spam</title>
    <link rel="stylesheet" href="style.css" media="screen" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;400&display=swap" rel="stylesheet">
    <script src='https://www.google.com/recaptcha/api.js?hl=en'></script>
  </head>
  <body>

    <div class="navbar">
      <a href="/">Home</a>
      <a href="/report">Report</a>
      <a href="/ip">IP</a>
      <a href="/delist" class="active">Delist</a>
      <a href="/admin">Admin</a>
    </div>

    <section>
      <h1>Check Status of an IP Addresses</h1>
      <form method="post" name="search" action="/search">
        <div class="field">
          <p>IP Address</p>
          <input type="text" name="ip" size="20">
        </div>
        <br>
        <div class="g-recaptcha" data-sitekey="6Lcix2sdAAAAAE2A5jRH9BOGoFrwA_Ajh9SRxpHq"></div>
        <br>
        <div class="submit-button">
          <button type="submit">Search</button>&nbsp;&nbsp;
          <button type="reset">Reset</button>
        </div>
      </form>
    </section>

<?php

$conn = mysqli_connect("localhost", "cs340_yanezr", "0735") or die(mysqli_error());
mysqli_select_db($conn, "cs340_yanezr") or die(mysqli_error());

$sql = "SELECT delist_id,delist_ip,delist_email,delist_date FROM delist";
$search = mysqli_query($conn,$sql) or die(mysqli_error($conn));
$match  = mysqli_num_rows($search);
if ( $match > 0 ) {
  print "<section>";
  print "<h1>Delist Requests</h1>";
  print "<div class='list'>";
  print "<table>";
  print "<tr>";
  print "<th>Delist ID</th>";
  print "<th>IP Address</th>";
  print "<th>Admin email</th>";
  print "<th>Delist Date</th>";
  print "<th>Confirmation Hash</th>";
  print "<th>Confirmation</th>";
  print "<th><br></th>";
  print "</tr>";

  while ( $row = mysqli_fetch_array($search) ) {
    $delist_id = mysqli_escape_string($conn, $row[0]);
    $delist_ip = mysqli_escape_string($conn, $row[1]);
    $email = mysqli_escape_string($conn, $row[2]);
    $date = mysqli_escape_string($conn, $row[3]);

    $sql1 = "SELECT confirmation_hash,confirmation_flag FROM confirmation_delist WHERE confirmation_id='".$delist_id."'";
    $search1 = mysqli_query($conn,$sql1) or die(mysqli_error($conn));
    $match1  = mysqli_num_rows($search1);
    if ( $match1 > 0 ) {
      while ( $row1 = mysqli_fetch_array($search1) ) {
        $hash = mysqli_escape_string($conn, $row1[0]);
        $flag = mysqli_escape_string($conn, $row1[1]);
      }
    }

    print "<tr>";
    print "<td>".$delist_id."</td>";
    print "<td>".$delist_ip."</td>";
    print "<td>".$email."</td>";
    print "<td>".$date."</td>";
    print "<td>".$hash."</td>";
    if ( $flag ) {
      print "<td align='center'>&#10003;</td>";
      print "<td><br></td>";
    }
    else {
      print "<td>";
      print "<form method='post' name='confirm_delist' action='/confirm_delist'>";
      print "<div class='confirm-button'>";
      print "<button type='submit'>Confirm</button>";
      print "</div>";
      print "<input type='hidden' name='delist_id' value='".$delist_id."'>";
      print "<input type='hidden' name='delist_ip' value='".$delist_ip."'>";
      print "</form>";
      print "</td>";

      print "<td>";
      print "<form method='post' name='delete_delist' action='/delete_delist'>";
      print "<div class='delete-button'>";
      print "<button type='submit'>Delete</button>";
      print "</div>";
      print "<input type='hidden' name='delist_id' value='".$delist_id."'>";
      print "</form>";
      print "</td>";    }
    print "</tr>";
  }
  print "</table>";
  print "<div>";
  print "</section>";
}

// close DB connection
mysqli_close($conn);

?>

  </body>
</html>
