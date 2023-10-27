<?php
session_start();

// Überprüfe, ob der Benutzer bereits angemeldet ist, um ihn zum Dashboard weiterzuleiten
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Projektverwaltung - Anmeldung</title>
  <link rel="stylesheet" type="text/css" href="/style.css">
</head>

<body>
  <div class="container">
    <h2>Projektverwaltung - Anmeldung</h2>
    <form action="login.php" method="POST">
      <input type="text" name="username" placeholder="Benutzername" required><br>
      <input type="password" name="password" placeholder="Passwort" required><br>
      <button type="submit">Anmelden</button>
    </form>
    <p>Noch kein Konto? <a href="register.php">Hier registrieren</a></p>
  </div>
</body>

</html>