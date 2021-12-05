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
  </head>
  <body>

    <div class="navbar">
      <a href="/">Home</a>
      <a href="/report">Report</a>
      <a href="/ip">IP</a>
      <a href="/delist">Delist</a>
      <a href="/admin" class="active">Admin</a>
    </div>

<?php

print "<section>";
print "<h1>IP Address Administrator</h1>";

$conn = mysqli_connect("localhost", "cs340_yanezr", "0735") or die(mysqli_error());
mysqli_select_db($conn, "cs340_yanezr") or die(mysqli_error());

$sql = "SELECT admin_id,admin_ip,admin_email FROM admin";
$search = mysqli_query($conn,$sql) or die(mysqli_error($conn));
$match  = mysqli_num_rows($search);

if ( $match > 0 ) {
  print "<div class='list'>";
  print "<table>";
  print "<tr>";
  print "<th>Admin ID</th>";
  print "<th>IP Address</th>";
  print "<th>Admin e-mail</th>";
  print "<th><br></th>";
  print "</tr>";

  while ( $row = mysqli_fetch_array($search) ) {
    $admin_id = mysqli_escape_string($conn, $row[0]);
    $admin_ip = mysqli_escape_string($conn, $row[1]);
    $admin_email = mysqli_escape_string($conn, $row[2]);

    print "<tr>";
    print "<td>".$admin_id."</td>";
    print "<td>".$admin_ip."</td>";
    print "<td>".$admin_email."</td>";
    print "<td>";
    print "<form method='post' name='confirm' action='/update_admin'>";
    print "<div class='confirm-button'>";
    print "<button type='submit'>Update</button>";
    print "</div>";
    print "<input type='hidden' name='admin_id' value='".$admin_id."'>";
    print "<input type='hidden' name='admin_email' value='".$admin_email."'>";
    print "<input type='hidden' name='flag' value='1'>";
    print "</form>";
    print "</td>";
    print "</tr>";
  }
  print "</table>";
  print "</div>";
}

// close DB connection
mysqli_close($conn);

print "</section>";

?>

  </body>
</html>
