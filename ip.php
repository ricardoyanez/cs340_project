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
      <a href="/ip" class="active">IP</a>
      <a href="/delist">Delist</a>
      <a href="/admin">Admin</a>
    </div>

<?php

if ( ($_SERVER['HTTP_REFERER'] == "https://cs340.calel.org/report") ) {
  if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if ( isset($_POST['g-recaptcha-response']) ) {
      $response_string = $_POST['g-recaptcha-response'];
      $my_secret="6LdYFWUUAAAAAPQvUavB-G0R8ETSz9Fsk3qs7_QA";
      $user_ip_address=$_SERVER['REMOTE_ADDR'];
      $output=shell_exec("curl 'https://www.google.com/recaptcha/api/siteverify?secret=$my_secret&response=$response_string&remoteip=$user_ip_address'");
      $recaptcha=json_decode($output,true);
      if ( !$recaptcha['success'] ) {
        print "<section>";
        print "<p>Only humans are allowed to use this site.</p>";
        print "</section>";
        exit();
      }
    }
    if ( !isset($_POST['email']) || empty($_POST['email']) || !isset($_POST['ip']) || empty($_POST['ip']) ||
         !isset($_POST['subject']) || empty($_POST['subject']) || !isset($_POST['header']) || empty($_POST['header']) ||
         !isset($_POST['body']) || empty($_POST['body']) ) {
      print "<section>";
      print "<p>All fields are required.</p>";
      print "</section>";
      exit();
    }
  }
}

$conn = mysqli_connect("localhost", "cs340_yanezr", "0735") or die(mysqli_error());
mysqli_select_db($conn, "cs340_yanezr") or die(mysqli_error());

if( isset($_POST['email']) && !empty($_POST['email']) AND isset($_POST['ip']) && !empty($_POST['ip']) AND
    isset($_POST['subject']) && !empty($_POST['subject']) AND isset($_POST['header']) && !empty($_POST['header']) AND
    isset($_POST['body']) && !empty($_POST['body']) ) {

  $email = mysqli_escape_string($conn, $_POST['email']);
  $report_ip = mysqli_escape_string($conn, $_POST['ip']);
  $subject = mysqli_escape_string($conn, $_POST['subject']);
  $header = mysqli_real_escape_string($conn, nl2br($_POST['header']));
  $body = mysqli_real_escape_string($conn, nl2br($_POST['body']));

  // validate data
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit("<section>$email is not a valid email address</section>");
  }
  if ( !filter_var($report_ip, FILTER_VALIDATE_IP) ) {
    exit("<section>$report_ip is not a valid IP address</section>");
  }

  $datetime = date('Y-m-d H:i:s');

  // insert REPORT data
  $sql = "INSERT INTO report (report_id,report_email,report_date,report_ip) VALUES (
    NULL,'".$email."','".$datetime."','".$report_ip."')";
  mysqli_query($conn,$sql) or die(mysqli_error($conn));

  // get report_id
  $search = mysqli_query($conn,"SELECT last_insert_id()") or die(mysqli_error($conn));
  $match  = mysqli_num_rows($search);
  if ( $match == 1 ) {
    while ( $row = mysqli_fetch_array($search) ) {
      $report_id = mysqli_escape_string($conn, $row[0]);
    }
  }

  $hash_str = $report_ip.time();
  $hash = md5($hash_str);

  // insert CONFIRMATION data
  $sql = "INSERT INTO confirmation_report (confirmation_id,confirmation_hash) VALUES (
    '".$report_id."','".$hash."')";
  mysqli_query($conn,$sql) or die(mysqli_error($conn));

  // insert SPAM data
  $sql = "INSERT INTO spam (spam_id,spam_subject,spam_header,spam_body) VALUES (
    '".$report_id."','".$subject."','".nl2br($header)."','".nl2br($body)."')";
  mysqli_query($conn,$sql) or die(mysqli_error($conn));

  // insert data into IP table
  $sql = "INSERT INTO ip (ip_id,ip_address) VALUES ('".$report_id."','".$report_ip."')";
  mysqli_query($conn,$sql) or die(mysqli_error($conn));
}

print "<section>";
print "<h1>IP Addresses</h1>";

$sql = "SELECT report_id,report_email,report_date,report_ip FROM report";
$search = mysqli_query($conn,$sql) or die(mysqli_error($conn));
$match  = mysqli_num_rows($search);

if ( $match > 0 ) {
  print "<div class='list'>";
  print "<table>";
  print "<tr>";
  print "<th>Report ID</th>";
  print "<th>IP Address</th>";
  print "<th>Report e-mail</th>";
  print "<th>Report Date</th>";
  print "<th>Subject</th>";
  print "<th>Confirmation</th>";
  print "<th><br></th>";
  print "</tr>";

  while ( $row = mysqli_fetch_array($search) ) {
    $report_id = mysqli_escape_string($conn, $row[0]);
    $report_email = mysqli_escape_string($conn, $row[1]);
    $report_date = mysqli_escape_string($conn, $row[2]);
    $report_ip = mysqli_escape_string($conn, $row[3]);

    $sql1 = "SELECT confirmation_hash,confirmation_flag FROM confirmation_report WHERE confirmation_id='".$report_id."'";
    $search1 = mysqli_query($conn,$sql1) or die(mysqli_error($conn));
    $match1  = mysqli_num_rows($search1);
    if ( $match1 > 0 ) {
      while ( $row1 = mysqli_fetch_array($search1) ) {
        $hash = mysqli_escape_string($conn, $row1[0]);
        $flag = mysqli_escape_string($conn, $row1[1]);
      }
    }

    $sql1 = "SELECT spam_subject FROM spam WHERE spam_id='".$report_id."'";
    $search1 = mysqli_query($conn,$sql1) or die(mysqli_error($conn));
    $match1  = mysqli_num_rows($search1);
    if ( $match1 > 0 ) {
      while ( $row1 = mysqli_fetch_array($search1) ) {
        $subject = mysqli_escape_string($conn, $row1[0]);
      }
    }

    print "<tr>";
    print "<td>".$report_id."</td>";
    print "<td>".$report_ip."</td>";
    print "<td>".$report_email."</td>";
    print "<td>".$report_date."</td>";
    print "<td>".$subject."</td>";
    if ( $flag ) {
      print "<td align='center'>&#10003;</td>";
      print "<td><br></td>";
    }
    else {
      print "<td>";
      print "<form method='post' name='confirm_report' action='/confirm_report'>";
      print "<div class='confirm-button'>";
      print "<button type='submit'>Confirm</button>";
      print "</div>";
      print "<input type='hidden' name='report_id' value='".$report_id."'>";
      print "</form>";
      print "</td>";

      print "<td>";
      print "<form method='post' name='delete_report' action='/delete_report'>";
      print "<div class='delete-button'>";
      print "<button type='submit'>Delete</button>";
      print "</div>";
      print "<input type='hidden' name='report_id' value='".$report_id."'>";
      print "</form>";
      print "</td>";
    }
    print "</tr>";
  }
  print "</table>";
  print "</div>";
}

mysqli_close($conn);
print "</section>";

?>

  </body>
</html>
