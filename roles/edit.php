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
if ( isset($_POST['role_name']) && isset($_POST['event_date']) &&
     isset($_POST['event_time']) ) {

  if ( ( strlen($_POST['role_name']) < 1) ||
     ( strlen($_POST['event_date']) < 1) ||
     ( strlen($_POST['event_time']) < 1) ) {
    $_SESSION['error'] = "Name and Date/Time are required.";
    header("Location: edit.php?role_id".$_POST['role_id']);
    return;
  }


  $sql = "UPDATE events
         SET role_name = :rn, notes = :no
         WHERE role_id = :rid";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
    ':rid' => $_POST['role_id'],
    ':rn' => $_POST['role_name'],
    ':no' => $_POST['notes'])
  );
  $role_id = $pdo->lastInsertId();

  // insert the education entries
  insertEducations($pdo, $role_id);

  $_SESSION['success'] = "Role updated";
  header('Location: ./index.php');
  return;
}

if ( ! isset($_GET['role_id']) ) {
  $_SESSION['error'] = "Missing role_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM roles WHERE role_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['role_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
  $_SESSION['error'] = 'Bad value for role_id';
  header('Location: index.php');
  return;
}

$rn = htmlentities($row['role_name']);
$rid = $row[('role_id')];

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
<p>Role Name:
<input type="text" name="role_name" size="60" value="<?= $rn ?>"/></p>
<p>Notes:<br/>
<textarea name="notes" rows="8" cols="80"><?= $no ?></textarea>
<p>
</p>
<p>
<input type="hidden" name="role_id" value="<?= $rid ?>"/>
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
