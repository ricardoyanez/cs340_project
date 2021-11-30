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
      <a href="/report" class="active">Report</a>
      <a href="/ip">IP</a>
      <a href="/delist">Delist</a>
      <a href="/admin">Admin</a>
    </div>

    <section>
      <h1>Report Spam</h1>
      <form method="post" name"report" action="/ip">
        <div class="field">
          <p>Contact e-mail</p>
          <input type="email" name="email" size="64" maxlength="64">
        </div>
        <div class="field">
          <p>IP address</p>
          <input type="text" name="ip" size="20">
        </div>
        <div class="field">
          <p>Subject</p>
          <input type="text" name="subject" size="64" maxlength="64">
        </div>
        <div class="field">
          <p>Header</p>
          <textarea name="header" rows="10" cols="80"></textarea>
        </div>
        <div class="field">
          <p>Body</p>
          <textarea name="body" rows="10" cols="80"></textarea>
        </div>
        <br>
        <div class="g-recaptcha" data-sitekey="6LdYFWUUAAAAANf9WHj4SLY8-Jhw76mluZzU3o4K"></div>
        <br>
        <div class="submit-button">
          <button type="submit">Submit</button>&nbsp;&nbsp;
          <button type="reset">Reset</button>
        </div>
      </form>
    </section>
  </body>
</html>
