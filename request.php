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

if ( ($_SERVER['HTTP_REFERER'] == "https://cs340.calel.org/search") ) {
  if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if ( isset($_POST['g-recaptcha-response']) ) {
      $response_string = $_POST['g-recaptcha-response'];
      $my_secret="6LdYFWUUAAAAAPQvUavB-G0R8ETSz9Fsk3qs7_QA"; // fake secret key
      $user_ip_address=$_SERVER['REMOTE_ADDR'];
      $output=shell_exec("curl 'https://www.google.com/recaptcha/api/siteverify?secret=$my_secret&response=$response_string&remoteip=$user_ip_address'");
      $recaptcha=json_decode($output,true);
      if ( !$recaptcha['success'] ) {
        exit("<section></p>Only humans are allowed to use this site.</p></section>");
      }
    }
    if ( !isset($_POST['email']) || empty($_POST['email']) || !isset($_POST['ip']) || empty($_POST['ip']) ||
         !isset($_POST['subject']) || empty($_POST['subject']) ) {
      exit("<section></p>All fields are required.</p></section>");
    }
    else {

      $conn = mysqli_connect("localhost", "cs340_yanezr", "0735") or die(mysqli_error());
      mysqli_select_db($conn, "cs340_yanezr") or die(mysqli_error());

      $delist_ip = mysqli_escape_string($conn, $_POST['ip']);
      $delist_email = mysqli_escape_string($conn, $_POST['email']);
      $subject = mysqli_escape_string($conn, $_POST['subject']);

      // validate data
      if (!filter_var($delist_email, FILTER_VALIDATE_EMAIL)) {
        exit("<section>$delist_email is not a valid email address</section>");
      }
      if ( !filter_var($delist_ip, FILTER_VALIDATE_IP) ) {
        exit("<section>$delist_ip is not a valid IP address</section>");
      }

      $datetime = date('Y-m-d H:i:s');

      // insert DELIST data
      $sql = "INSERT INTO delist (delist_id,delist_ip,delist_email,delist_date) VALUES (
        NULL,'".$delist_ip."','".$delist_email."','".$datetime."')";
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
        '".$delist_id."','".$delist_ip."','".$delist_email."')";
      mysqli_query($conn,$sql) or die(mysqli_error($conn));

      // close DB connection
      mysqli_close($conn);

      print "<section>";
      print "<h1>Confirm Delist</h1>";
      print "<div class='list'>";
      print "<table>";
      print "<tr>";
      print "<th>Delist ID</th>";
      print "<th>Delist e-mail</th>";
      print "<th>IP Address</th>";
      print "<th>Subject</th>";
      print "<th>Confirmation hash</th>";
      print "<th><br></th>";
      print "</tr>";
      print "<tr>";
      print "<td>".$delist_id."</td>";
      print "<td>".$delist_email."</td>";
      print "<td>".$delist_ip."</td>";
      print "<td>".$subject."</td>";
      print "<td>".$hash."</td>";
      print "</table>";
      print "</div>";
      print "<br>";

      print "<table>";
      print "<tr>";
      print "<td>";
      print "<form method='post' name='confirm_delist' action='/confirm_delist'>";
      print "<div class='confirm-button'>";
      print "<button type='submit'>Confirm</button>";
      print "</div>";
      print "<input type='hidden' name='delist_id' value='".$delist_id."'>";
      print "<input type='hidden' name='delist_ip' value='".$delist_ip."'>";
      print "</form>";
      print "</td>";
      print "<td><br></td>";
      print "<td>";
      print "<form method='post' name='delete_delist' action='/delete_delist'>";
      print "<div class='delete-button'>";
      print "<button type='submit'>Delete</button>";
      print "</div>";
      print "<input type='hidden' name='delist_id' value='".$delist_id."'>";
      print "</form>";
      print "</td>";
      print "</table>";

    }
  }
}

?>

  </body>
</html>
