<?php
session_start();

$host = 'localhost';
$db = 'project_management';
$user = 'root';
$password = '';

// Verbindung herstellen
$conn = mysqli_connect('$host', '$user', '$password', '$db');

// Überprüfen des Benutzerlogins
function checkLogin()
{
  if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
  }
}

// Aufgabe hinzufügen
if (
  $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['project_id']) && isset($_POST['task_name']) &&
  isset($_POST['task_description'])
) {
  checkLogin();

  $project_id = $_POST['project_id'];
  $task_name = $_POST['task_name'];
  $task_description = $_POST['task_description'];

  $query = "INSERT INTO tasks (project_id, name, description) VALUES ($project_id, '$task_name', '$task_description')";
  mysqli_query($conn, $query);

  header("Location: tasks.php?project_id=$project_id");
  exit();
}