<!--
Author: Ricardo Yanez <yanezr@oregonstate.edu>
Date: 11/23/2021

Course: CS 340 (Introduction to Databases)
 -->

<!DOCTYPE html>
<html>
  <head>
    <title>Report and Delist Spam</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="style.css" media="screen" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;400&display=swap" rel="stylesheet">
  </head>
  <body>

    <div class="navbar">
      <a href="/">Home</a>
      <a href="/report">Report</a>
      <a href="/ip">IP</a>
      <a href="/delist" class="active">Delist</a>
      <a href="/admin">Admin</a>
    </div>

<?php

$conn = mysqli_connect("localhost", "cs340_yanezr", "0735") or die(mysqli_error());
mysqli_select_db($conn, "cs340_yanezr") or die(mysqli_error());

if( isset($_POST['ip']) && !empty($_POST['ip']) ) {

  $ip = mysqli_escape_string($conn, $_POST['ip']);

  $sql = "SELECT ip_id FROM ip WHERE ip_address='".$ip."' AND ip_listed='1'";
  $search = mysqli_query($conn,$sql) or die(mysqli_error($conn));
  $match  = mysqli_num_rows($search);
  if ( $match > 0 ) {

    print "<section>";
    print "<h1>IP Address ".$ip."</h1>";
    print "</section>";

    while ( $row = mysqli_fetch_array($search) ) {
      $id = mysqli_escape_string($conn, $row[0]);

      $sql1 = "SELECT spam_subject,spam_header,spam_body FROM spam WHERE spam_id='".$id."'";
      $search1 = mysqli_query($conn,$sql1) or die(mysqli_error($conn));
      $match1  = mysqli_num_rows($search1);
      if ( $match1 == 1 ) {
        while ( $row1 = mysqli_fetch_array($search1) ) {
          $subject = mysqli_escape_string($conn, $row1[0]);
          $header = mysqli_escape_string($conn, $row1[1]);
          $body = mysqli_escape_string($conn, $row1[2]);

          $header = str_replace('\r\n','', $header);
          $body = str_replace('\r\n','', $body);

          print "<section>";
          print "<table>";

          print "<tr>";
          print "<th>Header</th>";
          print "</tr>";
          print "<tr>";
          print "<td>".$header."</td>";
          print "</tr>";

          print "<tr>";
          print "<th>Subject</th>";
          print "</tr>";
          print "<tr>";
          print "<td>".$subject."</td>";
          print "</tr>";

          print "<tr>";
          print "<th>Body</th>";
          print "</tr>";
          print "<tr>";
          print "<td>".$body."</td>";
          print "</tr>";

          print "</table>";
          print "</section><br>";
        }
      }
    }
    print "<section>";
    print "<form method='post' name='request' action='/request'>";
    print "<div class='field'>";
    print "<p>Contact Email</p>";
    print "<input type='text' name='email' size='60'>";
    print "</div>";
    print "<div class='field'>";
    print "<div class='submit-button'>";
    print "<button type='submit'>Delist</button>";
    print "</div>";
    print "<input type='hidden' name='ip' value='".$ip."'>";
    print "</form>";
    print "</section>";

  }
  else {
    print "<section>";
    print "<p>IP Address ".$ip." is not listed.</p>";
    print "</section>";
  }
}
mysqli_close($conn);
?>

  </body>
</html>
