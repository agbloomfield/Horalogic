<?php
require_once "../pdo.php";
session_start();

if ( isset($_POST['delete']) && isset($_POST['event_id']) ) {
    $sql = "DELETE FROM volunteers WHERE event_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':zip' => $_POST['event_id']));
    $_SESSION['success'] = 'Event deleted';
    header( 'Location: index.php' ) ;
    return;
}
if ( isset($_POST['cancel']) ){
  header( 'Location: index.php' ) ;
  return;
}

// Guardian: Make sure that user_id is present
if ( ! isset($_GET['event_id']) ) {
  $_SESSION['error'] = "Missing event_id";
  header('Location: index.php');
  return;
}

if ( isset($_POST['cancel']) ){
  header( 'Location: index.php' ) ;
  return;
}

$stmt = $pdo->prepare("SELECT event_name, event_time, event_date, event_id
                       FROM events where event_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['event_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for event_id';
    header( 'Location: index.php' );
    return;
}

?>
<!DOCTYPE html>
<html>
<head>
<title>Horalogic Deleting...</title>
<?php require_once "../head.php"; ?>
</head>
<div class="container">
<h1>Confirm: Deleting Event</h1>
  <form method="post" action="delete.php">
  <p>Event Name: <?= htmlentities($row['event_name']) ?></p>
  <p>Event Time: <?= htmlentities($row['event_time']) ?></p>
  <p>Event Date: <?= htmlentities($row['event_date']) ?></p>
  <input type="hidden" name="event_id" value="<?= $row['event_id'] ?>">
  <input type="submit" value="Delete" name="delete">
  <input type="submit" value="Cancel" name="cancel"</a>
</form>
</div>
</html>
