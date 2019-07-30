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
<div class="jumbotron jumbotro-fluid">
<h1 class="display-4">Welcome to Horalogic</h1>

<?php
if ( isset($_SESSION['error']) ) {
    echo '<div class="alert alert-danger">'.$_SESSION['error']."</div>\n";
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo '<div class="alert alert-success">'.$_SESSION['success']."</div>\n";
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
