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
  header('Location: ./index.php');
  return;
}

//Handle POST data
if ( isset($_POST['event_name']) && isset($_POST['event_date']) &&
     isset($_POST['event_time']) ) {

  if ( ( strlen($_POST['event_name']) < 1) ||
     ( strlen($_POST['event_date']) < 1) ||
     ( strlen($_POST['event_time']) < 1) ) {
    $_SESSION['error'] = "Name and Date/Time are required.";
    header("Location: edit.php?event_id".$_POST['event_id']);
    return;
  }


  $sql = "UPDATE events
         SET event_name = :en, event_date = :ed,
         event_time = :et
         WHERE event_id = :eid";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
    ':eid' => $_POST['event_id'],
    ':en' => $_POST['event_name'],
    ':ed' => $_POST['event_date'],
    ':et' => $_POST['event_time'])
  );
  $event_id = $pdo->lastInsertId();

  // insert the education entries
  insertEducations($pdo, $event_id);

  $_SESSION['success'] = "Event updated";
  header('Location: ./index.php');
  return;
}

if ( ! isset($_GET['event_id']) ) {
  $_SESSION['error'] = "Missing event_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM events WHERE event_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['event_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
  $_SESSION['error'] = 'Bad value for event_id';
  header('Location: index.php');
  return;
}

$en = htmlentities($row['event_name']);
$ed = htmlentities($row['event_date']);
$et = htmlentities($row['event_time']);
$eid = $row[('event_id')];

?>

<!DOCTYPE html>
<html>
<head>
<title>Horalogic</title>
<?php require_once '../head.php'; ?>
</head>
<body>
<div class="container">
<h1>Editing Event for <?php echo($_SESSION['name']); ?></h1>
<?php
// Flash Pattern
if ( isset($_SESSION['error']) ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
} ?>
<form method="post">
<p>Event Name:
<input type="text" name="event_name" size="60" value="<?= $en ?>"/></p>
<p>Event Date:
<input type="date" name="event_date" size="60" value="<?= $ed ?>"/></p>
<p>Event Time:
<input type="time" name="event_time" size="30" value="<?= $et ?>"/></p>
</p>
<p>
<input type="hidden" name="event_id" value="<?= $eid ?>"/>
<input type="submit" value="Save">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>

<!-- HTML with Substitution -->
<script id="edu-template" type="text">
  <div id="edu@COUNT@">
    <p>Year: <input type="text" name="edu_year@COUNT@" value="" />
    <input type="button" value="-" onclick="$('#edu@COUNT@').remove();return false;"><br>
    <p>School: <input type="text" size="80" name="edu_school@COUNT@" class="school" value="" />
    </p>
  </div>
</script>
</div>
</body>
</html>
