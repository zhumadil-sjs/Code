<?php
session_start();

// Überprüfe, ob der Benutzer bereits angemeldet ist, um ihn zum Dashboard weiterzuleiten
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit;
}

// Überprüfe die Registrierungsdaten
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Stelle eine Verbindung zur Datenbank her und füge den Benutzer hinzu
    // Hier musst du deine eigenen Datenbankverbindungsinformationen einfügen
    $conn = new mysqli("localhost", "root", "", "projektverwaltung");
    

    if ($conn->connect_error) {
        die("Verbindung zur Datenbank fehlgeschlagen: " . $conn->connect_error);
    }

    // Überprüfe, ob der Benutzer bereits existiert
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        // Füge den neuen Benutzer zur Datenbank hinzu
        $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
        if ($conn->query($sql) === TRUE) {
            // Benutzer erfolgreich registriert, speichere den Benutzernamen in der Sitzung
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
        } else {
            $error = "Fehler bei der Registrierung";
        }
    } else {
        $error = "Benutzername bereits vergeben";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Projektverwaltung - Registrierung</title>
  <link rel="stylesheet" type="text/css" href="/style.css">
</head>

<body>
  <div class="container">
    <h2>Projektverwaltung - Registrierung</h2>
    <form action="register.php" method="POST">
      <input type="text" name="username" placeholder="Benutzername" required><br>
      <input type="password" name="password" placeholder="Passwort" required><br>
      <button type="submit">Registrieren</button>
    </form>
    <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
    <p>Bereits ein Konto? <a href="index.php">Hier anmelden</a></p>
  </div>
</body>

</html>