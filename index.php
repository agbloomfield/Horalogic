<?php
require_once "pdo.php";
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once "head.php"; ?>
<title>Horalogic</title>
</head>
<body>
<div class="container">
<h1>Welcome to Horalogic</h1>

<?php
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
}

if ( isset ($_SESSION['name']) ) {
    echo('<p><a href="volunteers/index.php">Volunteers</a>');
    echo(' | ');
    echo('<a href="events/index.php">Events</a>');
    echo(' | ');
    echo('<a href="logout.php">Logout</a></p>');
} else {
  echo "<p>";
  echo('<a href="login.php">Please log in</a>');
  echo("</p>");
}

?>

</div>
</body>
