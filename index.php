<?php
$user = "admin";
$password = "system32";
$database = "educato";
$table = "usuario";

try {
  $db = new PDO("mysql:host=localhost;dbname=$database", $user, $password);
  echo "<h2>USUARIOS</h2><ol>"; 
  foreach($db->query("SELECT nombre FROM $table") as $row) {
    echo "<li>" . $row['nombre'] . "</li>";
  }
  echo "</ol>";
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
