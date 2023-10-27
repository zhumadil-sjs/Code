<?php
session_start();
include('/OSPanel/domains/verwaltung/includes/db.php');


// Login-Seite
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'login') {
?>

<!DOCTYPE html>
<html>

<head>
  <title>Login</title>
  <style>
  body {
    font-family: Arial, sans-serif;
  }

  h1 {
    margin-bottom: 10px;
  }

  form {
    margin-bottom: 10px;
  }

  label {
    display: block;
    margin-bottom: 5px;
  }

  input[type="text"],
  input[type="password"] {
    margin-bottom: 10px;
  }
  </style>
</head>

<body>
  <h1>Login</h1>

  <form action="/login.php" method=" POST">
    <label for="username">Benutzername:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Passwort:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Login</button>
  </form>

</body>

</html>

<?php
  exit();
}

// Überprüfen des Benutzerlogins
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['username'] === 'admin' && $_POST['password'] === 'password') {
  $_SESSION['user_id'] = 1;
  header('Location: index.php');
  exit();
} else {
  header('Location: login.php');
  exit();
}
?>