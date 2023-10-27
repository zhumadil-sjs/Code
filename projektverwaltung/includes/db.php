<?php
  $servername = "localhost";
  $username = "root";
  $password = "";
  $database = "projektverwaltung";

  $conn = new mysqli($servername, $username, $password, $database);

  if ($conn->connect_error) {
    die("Verbindung zur Datenbank fehlgeschlagen: " . $conn->connect_error);
  }
?>