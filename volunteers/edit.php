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
if ( isset($_POST['first_name']) && isset($_POST['last_name']) &&
     isset($_POST['email']) && isset($_POST['notes']) ) {

  if ( ( strlen($_POST['first_name']) < 1) ||
     ( strlen($_POST['last_name']) < 1) ||
     ( strlen($_POST['email']) < 1) ) {
    $_SESSION['error'] = "Name and email are required.";
    header("Location: edit.php?volunteer_id".$_POST['volunteer_id']);
    return;
  }

  if ( strpos($_POST['email'], '@') === false ) {
    $_SESSION['error'] = "Email must contain at-sign (@)";
    header("Location: ./add.php");
    return;
  }

  $sql = "UPDATE volunteers
         SET first_name = :fn, last_name = :ln,
         email = :em, phone = :ph, notes = :no, user_id = :uid
         WHERE volunteer_id = :vid";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array(
    ':uid' => $_SESSION['user_id'],
    ':vid' => $_POST['volunteer_id'],
    ':fn' => $_POST['first_name'],
    ':ln' => $_POST['last_name'],
    ':em' => $_POST['email'],
    ':ph' => $_POST['phone'],
    ':no' => $_POST['notes'])
  );
  $volunteer_id = $pdo->lastInsertId();

  // insert the education entries
  insertEducations($pdo, $volunteer_id);

  $_SESSION['success'] = "Volunteer updated";
  header('Location: ./index.php');
  return;
}

if ( ! isset($_GET['volunteer_id']) ) {
  $_SESSION['error'] = "Missing volunteer_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM volunteers WHERE volunteer_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['volunteer_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
  $_SESSION['error'] = 'Bad value for volunteer_id';
  header('Location: index.php');
  return;
}

$fn = htmlentities($row['first_name']);
$ln = htmlentities($row['last_name']);
$em = htmlentities($row['email']);
$ph = htmlentities($row['phone']);
$no = htmlentities($row['notes']);
$vid = $row[('volunteer_id')];

?>

<!DOCTYPE html>
<html>
<head>
<title>Horalogic</title>
<?php require_once '../head.php'; ?>
</head>
<body>
<div class="container">
<h1>Editing Volunteer for <?php echo($_SESSION['name']); ?></h1>
<?php
// Flash Pattern
if ( isset($_SESSION['error']) ) {
    // Look closely at the use of single and double quotes
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
} ?>
<form method="post">
<p>First Name:
<input type="text" name="first_name" size="60" value="<?= $fn ?>"/></p>
<p>Last Name:
<input type="text" name="last_name" size="60" value="<?= $ln ?>"/></p>
<p>Email:
<input type="text" name="email" size="30" value="<?= $em ?>"/></p>
<p>Phone:<br/>
<input type="text" name="phone" size="80" value="<?= $ph ?>"/></p>
<p>Notes:<br/>
<textarea name="notes" rows="8" cols="80"><?= $no ?></textarea>
<p>
Event Types: <input type="submit" id="addEdu" value="+">
<div id="edu_fields">
</div>
</p>
<p>
<input type="hidden" name="volunteer_id" value="<?= $vid ?>"/>
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
