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

$conn = mysqli_connect("localhost", "cs340_yanezr", "0735") or die(mysqli_error());
mysqli_select_db($conn, "cs340_yanezr") or die(mysqli_error());

if( isset($_POST['admin_id']) && !empty($_POST['admin_id']) AND isset($_POST['admin_email']) && !empty($_POST['admin_email']) AND isset($_POST['flag']) && !empty($_POST['flag']) ) {

  $admin_id = mysqli_escape_string($conn, $_POST['admin_id']);
  $admin_email = mysqli_escape_string($conn, $_POST['admin_email']);

  // validate data
  if (!filter_var($admin_email, FILTER_VALIDATE_EMAIL)) {
    exit("<section>$admin_email is not a valid email address</section>");
  }

  if ( $_POST['flag'] == 1 ) {
    print "<section>";
    print "<h1>Update Administrator e-mail Address</h1>";
    print "<form method='post' name='update_admin' action='/update_admin'>";
    print "<div class='field'>";
    print "<p>New e-mail Address</p>";
    print "<input type='email' name='admin_email' size='20' placeholder='".$admin_email."'>";
    print "</div><br>";
    print "<input type='hidden' name='admin_id' value='".$admin_id."'>";
    print "<input type='hidden' name='flag' value='2'>";

    print "<table>";
    print "<tr>";
    print "<td>";
    print "<div class='submit-button'>";
    print "<button type='submit'>Update</button>";
    print "</div>";
    print "</form>";
    print "</td>";
    print "<td>&nbsp;<br></td>";
    print "<td>";
    print "<form method='post' name='update_admin' action='/admin'>";
    print "<div class='submit-button'>";
    print "<button type='submit'>Cancel</button>";
    print "</div>";
    print "</form>";
    print "</td>";
    print "</tr>";
    print "</table>";
    print "</section>";
  }
  else if ( $_POST['flag'] == 2 ){
    // update ADMIN table
    $sql = "UPDATE admin SET admin_email='".$admin_email."' WHERE admin_id='".$admin_id."'";
    mysqli_query($conn,$sql) or die(mysqli_error($conn));

    header("Location: admin");
  }

}

mysqli_close($conn);

?>

  </body>
</html>
