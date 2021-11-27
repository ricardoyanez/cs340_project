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
      <a href="/" class="active">Home</a>
      <a href="/report">Report</a>
      <a href="/ip">IP</a>
      <a href="/delist">Delist</a>
      <a href="/admin">Admin</a>
    </div>

    <section>
      <h1>Report and Delist Spam</h1>
      <p>The project is to create and provide a suitable database and web interface for Internet users to (1) report spam, and for administrators of email services to (2) delist a reported IP from a realtime blacklist (RBL). The spam report interface provides a few fields to be filled, a) the email address of the reporter, b) the header, c) the subject and d) the body of the spam message. The interface will also have a separate field to check if an IP address is listed or not, and provide the spam evidence against it if it is. From this point, an administrator may request a delisting of the IP address, whereby the administrator must provide an email address. The target blacklist typically has 10-15 spam reports per day, and some 5-10 delistings per day.</p>
    </section>

  </body>
</html>
