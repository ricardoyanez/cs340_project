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

<?php

if ( ($_SERVER['HTTP_REFERER'] == "https://cs340.calel.org/delist") ) {
  if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if ( isset($_POST['g-recaptcha-response']) ) {
      $response_string = $_POST['g-recaptcha-response'];
      // fake secret key
      $my_secret="secretkeyplaceholder";
      $user_ip_address=$_SERVER['REMOTE_ADDR'];
      $output=shell_exec("curl 'https://www.google.com/recaptcha/api/siteverify?secret=$my_secret&response=$response_string&remoteip=$user_ip_address'");
      $recaptcha=json_decode($output,true);
      if ( !$recaptcha['success'] ) {
        exit("<section></p>Only humans are allowed to use this site.</p></section>");
      }
    }
    if ( !isset($_POST['ip']) || empty($_POST['ip']) ) {
      exit("<section></p>All fields are required.</p></section>");
    }
    else {
      $conn = mysqli_connect("localhost", "cs340_yanezr", "0735") or die(mysqli_error());
      mysqli_select_db($conn, "cs340_yanezr") or die(mysqli_error());

      $ip = mysqli_escape_string($conn, $_POST['ip']);

      // validate data
      if ( !filter_var($ip, FILTER_VALIDATE_IP) ) {
        exit("<section></p>$ip is not a valid IP address.</p></section>");
      }

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
              print "<div class='list'>";
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
              print "</div>";
              print "</section><br>";
            }
          }
        }
        print "<section>";
        print "<form method='post' name='request' action='/request'>";
        print "<div class='field'>";
        print "<p>Contact Email</p>";
        print "<input type='email' name='email' size='60'>";
        print "</div><br>";
        print "<div class='field'>";
        print "<div class='submit-button'>";
        print "<button type='submit'>Delist</button>";
        print "</div>";
        print "<input type='hidden' name='ip' value='".$ip."'>";
        print "<input type='hidden' name='subject' value='".$subject."'>";
        print "</form><br>";
        print "</section>";

      }
      else {
        print "<section>";
        print "<p>IP Address ".$ip." is not listed.</p>";
        print "</section>";
      }

      // close DB connection
      mysqli_close($conn);
    }
  }
}

?>

  </body>
</html>
