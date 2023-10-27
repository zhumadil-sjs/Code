<?php
session_start();

// Überprüfe, ob der Benutzer bereits angemeldet ist, um ihn zum Dashboard weiterzuleiten
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit;
}

// Überprüfe die Benutzeranmeldeinformationen
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Stelle eine Verbindung zur Datenbank her und überprüfe die Anmeldeinformationen
    $conn = new mysqli("localhost", "root", "", "projektverwaltung");

    if ($conn->connect_error) {
        die("Verbindung zur Datenbank fehlgeschlagen: " . $conn->connect_error);
    }

    // SQL-Abfrage, um den Benutzer zu überprüfen
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Benutzer erfolgreich eingeloggt, speichere den Benutzernamen in der Sitzung
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
    } else {
        $error = "Ungültige Anmeldeinformationen";
    }

    $conn->close();
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
    <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
    <p>Noch kein Konto? <a href="register.php">Hier registrieren</a></p>
  </div>
</body>

</html>