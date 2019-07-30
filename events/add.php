<?php
require_once '../pdo.php';
require_once '../util.php';

session_start();

//If not logged in, redirect with error
if ( ! isset($_SESSION['name']) ) {
  die('ACCESS DENIED');
  return;
}

//If cancel clicked redirect to index.php
if ( isset($_POST['cancel']) ) {
  header('Location: index.php');
  return;
}

//Handle POST data
if ( isset($_POST['event_name']) && isset($_POST['event_date']) &&
     isset($_POST['event_time']) ) {

  if ( strlen($_POST['event_name']) < 1 ) {
    $_SESSION['error'] = "All fields are required.";
    header("Location: add.php");
    return;
  }

  $sql = "INSERT INTO events
         (event_name, event_date, event_time)
         VALUES ( :eid, :en, :ed, :et)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
    ':en' => $_POST['event_name'],
    ':ed' => $_POST['event_date'],
    ':et' => $_POST['event_time'])
  );


  $_SESSION['success'] = "Event added";
  header('Location: index.php');
  return;
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Horalogic | Scheduling Science</title>
<?php require_once '../head.php'; ?>
</head>
<body>
<div class="container">
<h1>Adding Events for <?php echo($_SESSION['name']); ?></h1>
<?php
// Flash Pattern
if ( isset($_SESSION['error']) ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
} ?>
<form method="post">
<p>Event Name:
<input type="text" name="event_name" size="60"/></p>
<p>Event Date:
<input type="date" name="event_date" /></p>
<p>Event Time:
<input type="time" name="event_time" size="30"/></p>
<p>
<input type="submit" value="Add">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>

</div>
</body>
</html>
