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
if ( isset($_POST['volunteer_id']) && isset($_POST['event_id']) &&
     isset($_POST['role_id']) ) {

  $sql = "INSERT INTO assignments
         (volunteer_id, event_id, role_id, notes)
         VALUES ( :vid, :eid, :rid, :no)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
    ':vid' => $_POST['volunteer_id'],
    ':eid' => $_POST['event_id'],
    ':rid' => $_POST['role_id'],
    ':no' => $_POST['notes'])
  );

  $volunteer_id = $pdo->lastInsertId();

  $_SESSION['success'] = "Volunteer assignment added";
  header('Location: ./index.php');
  return;
}

?>

<!DOCTYPE html>
<html>
<head>
<title>Horalogic</title>
<?php require_once '../head.php'; ?>
</head>
<body>
<div class="container">
<h1>Adding Assignments for <?php echo($_SESSION['name']); ?></h1>
<?php
// Flash Pattern
if ( isset($_SESSION['error']) ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
} ?>
<form method="post">
<p>Volunteer:
<?php
    $stmt = $pdo->query("SELECT volunteer_id, first_name, last_name FROM volunteers");
    echo "<select name='volunteer_id'>";
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
        echo "<option value='" . $row['volunteer_id'] .
              "'>" . $row['first_name'] . " " . $row['last_name'] . "</option>";
    }
    echo "</select>";
?>
<p>Event:
  <?php
      $stmt = $pdo->query("SELECT event_id, event_name, event_date, event_time FROM events");
      echo "<select name='event_id'>";
      while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
          echo "<option value='" . $row['event_id'] .
                "'>" . $row['event_name'] . " - " . $row['event_date'] . " - " . $row['event_time'] . "</option>";
      }
      echo "</select>";
  ?>
<p>Role:
  <?php
      $stmt = $pdo->query("SELECT role_id, role_name FROM roles");
      echo "<select name='role_id'>";
      while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
          echo "<option value='" . $row['role_id'] .
                "'>" . $row['role_name'] . "</option>";
      }
      echo "</select>";
  ?>
<p>Notes:<br/>
<textarea name="notes" rows="4" cols="80"></textarea>
<p>
<input type="submit" value="Assign">
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
