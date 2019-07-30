<?php
require_once "../pdo.php";
require_once "../head.php";
session_start();

$sql = "SELECT * FROM volunteers
        WHERE volunteer_id = :volunteer_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(':volunteer_id' => $_GET['volunteer_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
<title>Horalogic</title>
</head>
<body>
<div class="container">
<h1>Horalogic</h1>
<?php
  echo "<p>First Name: ".htmlentities($row['first_name'])."</p>";
  echo "<p>Last Name: ".htmlentities($row['last_name'])."</p>";
  echo "<p>Email: ".htmlentities($row['email'])."</p>";
  echo "<p>Phone: ".htmlentities($row['phone'])."</p>";
  echo "<p>Notes: <br/>".htmlentities($row['notes'])."</p>";
?>
</p>
<a href="index.php">Done</a>
</div>
</html>
